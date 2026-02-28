const API = "../../../Backend/Admin";
// const API = "../Backend/admin";

document.addEventListener("DOMContentLoaded", async () => {
    // 🔔 Campana
    const btnBell = document.getElementById("btnBell");
    const bellDropdown = document.getElementById("bellDropdown");
    btnBell.addEventListener("click", () => {
        bellDropdown.style.display = bellDropdown.style.display === "none" ? "block" : "none";
    });

    document.getElementById("btnMarkRead").addEventListener("click", async () => {
        await fetch(`${API}/notifs_marcar_leidas.php`, { method: "POST" });
        await cargarNotifs();
    });

    // FullCalendar
    const calEl = document.getElementById("calendar");
    const calendar = new FullCalendar.Calendar(calEl, {
        initialView: "dayGridMonth",
        height: "auto",
        locale: "es",
        editable: true,        // drag & drop
        eventStartEditable: true,
        eventDurationEditable: false,
        eventClick: (info) => abrirModalCita(info.event),
        eventDrop: async (info) => {
            // Drag & drop -> actualizar fecha
            const ok = await actualizarFecha(info.event.id, info.event.start);
            if (!ok) info.revert();
        },
        events: async (fetchInfo, successCallback, failureCallback) => {
            try {
                const url = `${API}/citas_listar.php?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`;
                const res = await fetch(url);
                const data = await res.json();
                successCallback(data);
            } catch (e) {
                failureCallback(e);
            }
        },
    });
    calendar.render();

    // polling notificaciones cada 10s
    await cargarNotifs();
    setInterval(cargarNotifs, 10000);
});

// ====== Acciones ======

async function actualizarFecha(idCita, startDate) {
    const payload = new URLSearchParams();
    payload.set("idCita", idCita);
    payload.set("fechaHora", startDate.toISOString().slice(0, 19).replace("T", " ")); // "YYYY-MM-DD HH:MM:SS"

    const res = await fetch(`${API}/citas_actualizar_fecha.php`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: payload.toString()
    });

    const json = await res.json();
    if (!json.ok) {
        Swal.fire("Error", json.msg || "No se pudo actualizar la fecha", "error");
        return false;
    }

    Swal.fire("Listo", "Fecha actualizada", "success");
    return true;
}

function abrirModalCita(event) {
    const idCita = event.id;
    const title = event.title;
    const start = event.start ? event.start.toLocaleString() : "";

    Swal.fire({
        title: "Cita",
        html: `<div style="text-align:left">
            <div><b>ID:</b> ${idCita}</div>
            <div><b>Cliente:</b> ${escapeHtml(title)}</div>
            <div><b>Fecha:</b> ${escapeHtml(start)}</div>
          </div>`,
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "✅ Completar",
        denyButtonText: "❌ Cancelar",
        cancelButtonText: "Cerrar",
        confirmButtonColor: "#2a9d8f",
        denyButtonColor: "#e63946",
    }).then(async (result) => {
        if (result.isConfirmed) {
            await cambiarEstado(idCita, "COMPLETADA");
            location.reload();
        } else if (result.isDenied) {
            await cambiarEstado(idCita, "CANCELADA");
            location.reload();
        }
    });
}

async function cambiarEstado(idCita, estado) {
    const payload = new URLSearchParams();
    payload.set("idCita", idCita);
    payload.set("estado", estado);

    const res = await fetch(`${API}/citas_cambiar_estado.php`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: payload.toString()
    });

    const json = await res.json();
    if (!json.ok) {
        Swal.fire("Error", json.msg || "No se pudo cambiar el estado", "error");
        return;
    }
    Swal.fire("Listo", json.msg || "Actualizado", "success");
}

// ====== Notificaciones ======

async function cargarNotifs() {
    const res = await fetch(`${API}/notifs_listar.php`);
    const json = await res.json();

    const countEl = document.getElementById("bellCount");
    const listEl = document.getElementById("bellList");

    const unread = json.unread || 0;
    if (unread > 0) {
        countEl.style.display = "inline-block";
        countEl.textContent = unread;
    } else {
        countEl.style.display = "none";
    }

    listEl.innerHTML = "";
    (json.items || []).forEach(n => {
        const div = document.createElement("div");
        div.style.padding = "10px";
        div.style.borderRadius = "10px";
        div.style.marginBottom = "8px";
        div.style.background = n.Leida ? "#222" : "#2b2b2b";
        div.innerHTML = `
      <div style="display:flex;justify-content:space-between;gap:10px;">
        <b>${escapeHtml(n.Titulo)}</b>
        <span style="opacity:.7;font-size:12px;">${escapeHtml(n.FechaTxt)}</span>
      </div>
      <div style="margin-top:6px;opacity:.9">${escapeHtml(n.Mensaje)}</div>
    `;
        listEl.appendChild(div);
    });
}

function escapeHtml(str) {
    return String(str ?? "").replace(/[&<>"']/g, m => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
    }[m]));
}





