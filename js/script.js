// Comprobar si ya hay una sesión activa al cargar la página
function comprobarSesion() {
    let token = localStorage.getItem("token");
    if (token) {
        document.getElementById("seccion-login").classList.add("oculto");
        document.getElementById("seccion-crud").classList.remove("oculto");
        listar();
    } else {
        document.getElementById("seccion-login").classList.remove("oculto");
        document.getElementById("seccion-crud").classList.add("oculto");
    }
}

// Función para iniciar sesión y obtener el JWT
async function login() {
    let usuario = document.getElementById("login-usuario").value;
    let password = document.getElementById("login-password").value;

    if (!usuario || !password) {
        Swal.fire("Campos vacíos", "Por favor ingresa tus credenciales", "warning");
        return;
    }

    try {
        let r = await fetch("api/login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ usuario, password })
        });

        let res = await r.json();

        if (res.success) {
            localStorage.setItem("token", res.token); // Guardamos el token
            Swal.fire("¡Bienvenido!", "Inicio de sesión correcto", "success");
            comprobarSesion();
        } else {
            Swal.fire("Error", "Usuario o contraseña incorrectos", "error");
        }
    } catch (error) {
        Swal.fire("Error Crítico", "No se pudo conectar con el Login", "error");
    }
}

function cerrarSesion() {
    localStorage.removeItem("token");
    comprobarSesion();
}

// --- CRUD PROTEGIDO CON COPIAS DE SEGURIDAD ---

async function listar() {
    let token = localStorage.getItem("token");
    try {
        let r = await fetch("api/productos.php", {
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token // Mandamos el token al servidor
            }
        });
        
        let data = await r.json();
        let html = "";

        data.forEach(p => {
            html += `
            <tr>
                <td>${p.id}</td>
                <td>${p.codigo}</td>
                <td>${p.producto}</td>
                <td>$${parseFloat(p.precio).toFixed(2)}</td>
                <td>${p.cantidad}</td>
            </tr>`;
        });
        document.getElementById("tabla").innerHTML = html;
    } catch (error) {
        console.error("Error al listar:", error);
    }
}

async function guardar() {
    let token = localStorage.getItem("token");
    let datos = {
        codigo: document.getElementById("codigo").value,
        producto: document.getElementById("producto").value,
        precio: document.getElementById("precio").value,
        cantidad: document.getElementById("cantidad").value
    };

    if(!datos.codigo || !datos.producto || !datos.precio || !datos.cantidad) {
        Swal.fire("Error", "Por favor, llena todos los campos", "error");
        return;
    }

    try {
        let r = await fetch("api/productos.php", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token // Mandamos el token al servidor
            },
            body: JSON.stringify(datos)
        });

        let res = await r.json();

        if(res.success) {
            Swal.fire("Guardado", "Producto registrado con éxito", "success");
            document.getElementById("codigo").value = "";
            document.getElementById("producto").value = "";
            document.getElementById("precio").value = "";
            document.getElementById("cantidad").value = "";
            listar();
        }
    } catch (error) {
        Swal.fire("Error", "No se pudo guardar el producto", "error");
    }
}

// Ejecutar al cargar la página
comprobarSesion();