DOCUMENTACIÓN DEL PROYECTO
CRUD DE PRODUCTOS CON FETCH API, PHP OOP, MYSQL Y SEGURIDAD JWT
Universidad Tecnológica de Panamá Facultad de Ingeniería en Sistemas Computacionales (FISC) Licenciatura en Desarrollo de Software Asignatura: Desarrollo de Software VII
Estudiante: Ruben Dominguez
Grupo: 1GS-131
Profesor: Ing. Irina Fong
Fecha: 16/6/2026
1. INTRODUCCIÓN
Actualmente las aplicaciones web modernas requieren una comunicación eficiente entre el cliente y el servidor para intercambiar información en tiempo real sin recargar la página del navegador. Para lograrlo se utilizan arquitecturas desacopladas basadas en servicios web, empleando tecnologías como Fetch API, formato JSON, PHP Orientado a Objetos (POO) y sistemas de gestión de bases de datos relacionales.
En este proyecto se desarrolló un sistema completo CRUD (Create, Read, Update, Delete) para la gestión de inventario de productos. El sistema implementa una arquitectura estrictamente Cliente-Servidor. El frontend (Cliente) realiza solicitudes HTTP asíncronas asistiéndose de la API Fetch de JavaScript nativo, mientras que el backend (Servidor) procesa dichas peticiones mediante scripts controladores en PHP que interactúan con un modelo de datos estructurado en clases y objetos conectados a MySQL.
Adicionalmente, se implementó una API RESTful protegida mediante tokens JWT (JSON Web Token). Esta estrategia permite controlar el acceso a los recursos de manera segura e inalámbrica (stateless), garantizando que únicamente aquellos usuarios previamente autenticados mediante credenciales válidas puedan interactuar con los endpoints de la base de datos. El sistema integra de punta a punta conceptos fundamentales del desarrollo de software profesional: persistencia de datos, hashing algorítmico de contraseñas, validación en dos capas (cliente/servidor), manejo de cabeceras CORS y pruebas unitarias de peticiones mediante Postman.
2. OBJETIVOS
Objetivo General
•	Diseñar, codificar e implementar una aplicación web bajo el patrón cliente-servidor utilizando PHP 8, MySQL y JavaScript que administre productos mediante un CRUD asíncrono y exponga los recursos a través de una API REST protegida criptográficamente con JSON Web Tokens (JWT).
Objetivos Específicos
•	Implementar operaciones completas CRUD mapeadas a los verbos HTTP estándares (GET, POST, PUT, DELETE).
•	Estructurar el backend mediante Programación Orientada a Objetos en PHP, aislando la lógica de negocio de la lógica de conexión.
•	Gestionar el acceso a la base de datos MySQL mediante la interfaz PDO (PHP Data Objects), aplicando consultas preparadas para mitigar vulnerabilidades.
•	Desarrollar un cliente asíncrono interactivo consumiendo la Fetch API apoyado de promesas y funciones async/await.
•	Estandarizar la comunicación entre cliente y servidor exclusivamente mediante estructuras de datos en formato JSON.
•	Diseñar un módulo de autenticación centralizado que verifique credenciales mediante password_verify() y expida llaves criptográficas JWT firmadas con el algoritmo HS256.
•	Implementar seguridad en el servidor mediante la interceptación y decodificación de cabeceras HTTP de tipo Authorization: Bearer <token>.
•	Configurar políticas de acceso CORS para delimitar de manera segura los métodos y orígenes permitidos por la API.
•	Validar la integridad de los datos en dos niveles: validaciones de interfaz en el cliente y validaciones lógicas restrictivas en el servidor.
•	Comprobar la resiliencia de los endpoints expuestos mediante la suite de pruebas Postman.
3. TECNOLOGÍAS UTILIZADAS
Tecnología	Categoría	Descripción Técnica
HTML5	Frontend	Define el marcado semántico y la estructura del formulario de login y la tabla del CRUD.
CSS3	Frontend	Hojas de estilo personalizadas para animaciones y ocultamiento dinámico de interfaces (.oculto { display: none; }).
Bootstrap 5.3	Frontend	Framework de diseño adaptativo encargado de dotar al software de una interfaz responsiva (Grid System, Cards, Forms).
JavaScript (ES6+)	Frontend	Motor lógico del cliente; gestiona el DOM, manipula el almacenamiento local (localStorage) y procesa eventos.
Fetch API	Frontend	Interfaz asíncrona nativa de JavaScript para ejecutar promesas HTTP (Request/Response) sobre la red.
PHP 8.x	Backend	Entorno de ejecución en el servidor; lenguaje encargado de procesar solicitudes, validar JWT y ejecutar la POO.
MySQL 8.0	Base de Datos	Motor relacional para el almacenamiento persistente de las entidades productos y usuarios.
PDO	Backend	Capa de abstracción de datos en PHP que provee un controlador seguro, rápido y orientado a objetos para interactuar con MySQL.
JSON	Formato	Estándar de intercambio de texto ligero basado en llaves y valores utilizado como idioma único de comunicación.
JWT (Firebase)	Seguridad	Librería estándar de seguridad encargada de codificar, decodificar y firmar digitalmente tokens de acceso usando llaves de 256 bits.
Composer	Dependencias	Gestor de paquetes de PHP encargado de instalar y mapear de forma automática la librería firebase/php-jwt.
Postman	Pruebas	Entorno de desarrollo de APIs utilizado para simular peticiones HTTP directas y analizar respuestas en crudo.
SweetAlert2	Frontend	Librería de JavaScript utilizada para renderizar ventanas flotantes y alertas estéticas dinámicas (Modals de éxito/error).
4. ARQUITECTURA DEL SISTEMA
La aplicación se cimenta sobre una arquitectura desacoplada de dos capas basada en el patrón de diseño Cliente-Servidor (Stateless API).
Capa Cliente (Frontend)
El cliente opera de forma aislada en el navegador web del usuario y no posee conexión directa con la base de datos. Se encarga de:
1.	Captura y Orquestación: Recopila las entradas del usuario a través de formularios reactivos controlados por eventos de escucha (addEventListener).
2.	Seguridad Local: Captura el token devuelto por el servidor y lo almacena de manera persistente en el hilo del navegador utilizando la API localStorage.
3.	Petición síncrona/asíncrona: Construye peticiones HTTP inyectando de forma dinámica el token guardado en el encabezado Authorization: Bearer <token>.
4.	Renderizado Dinámico: Destruye y reconstruye el árbol del DOM de forma dinámica (inyectando filas en la tabla o conmutando pantallas entre el Login y el CRUD) sin refrescar la ventana.
Capa Servidor (Backend)
El servidor actúa como una API REST centralizada que expone endpoints lógicos. Se encarga de:
1.	Control de Acceso (Gatekeeper): Intercepta la petición, extrae las cabeceras, inicializa el autoloader de Composer y valida la vigencia y firma criptográfica del JWT antes de delegar el flujo.
2.	Enrutamiento (Switching Métodos): Examina la propiedad global $_SERVER['REQUEST_METHOD'] para discernir si la operación solicitada equivale a una lectura, escritura, edición o borrado.
3.	Capa del Modelo (POO): Instancia clases lógicas que encapsulan las querysSQL en métodos específicos.
4.	Respuestas Tipificadas: Emite códigos de estado HTTP semánticos (Ej: 200 OK, 401 Unauthorized, 500 Internal Error) acompañados estrictamente de respuestas codificadas en JSON a través de json_encode().
5. DISEÑO DE LA BASE DE DATOS
El esquema de la base de datos, denominado productosdb, se compone de dos tablas independientes estructuradas bajo restricciones de integridad relacional:
Tabla: productos
Almacena el inventario físico de productos gestionados por el sistema.
SQL
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    producto VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
Diccionario de Datos de la Tabla productos:
•	id (INT): Clave primaria autoincremental. Indexada automáticamente para búsquedas rápidas.
•	codigo (VARCHAR): Descriptor único comercial del producto. Posee restricción UNIQUE para evitar colisiones en inventario.
•	producto (VARCHAR): Almacena el nombre o descripción literal del artículo.
•	precio (DECIMAL): Formato numérico de alta precisión con dos posiciones decimales. Evita errores de redondeo flotante.
•	cantidad (INT): Valor entero no negativo que representa las existencias actuales en stock.
Tabla: usuarios
Almacena las credenciales de los operadores facultados para firmar operaciones en la API.
SQL
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
Diccionario de Datos de la Tabla usuarios:
•	id (INT): Clave primaria autoincremental de control interno.
•	usuario (VARCHAR): Nombre de usuario único (username) utilizado en el login.
•	password (VARCHAR): Cadena de alta longitud diseñada para albergar de forma segura el hash criptográfico resultante de la función PASSWORD_BCRYPT. Nunca se guarda en texto plano.
6. ESTRUCTURA COMPLETA DEL PROYECTO
La organización de archivos responde a un patrón limpio que separa los controladores lógicos, los componentes del modelo y las vistas de la interfaz:
Plaintext
CRUDJWT/
│
├── api/                       <-- Controladores de la API (Endpoints REST)
│   ├── login.php              <-- Endpoint para autenticación y expedición de tokens
│   └── productos.php          <-- Endpoint CRUD para la manipulación de productos
│
├── config/                    <-- Configuraciones globales del sistema
│   └── config.php             <-- Definición de constantes y la clave secreta JWT
│
├── Modelo/                    <-- Capa de Persistencia y Acceso a Datos (Modelos)
│   ├── conexion.php           <-- Clase de conexión PDO a la Base de Datos
│   └── Producto.php           <-- Clase POO con métodos SQL de la entidad Producto
│
├── js/                        <-- Lógica del Lado del Cliente
│   └── script.js              <-- Controlador Fetch API y manipulador del DOM
│
├── vendor/                    <-- Dependencias de Terceros (Generada por Composer)
│   ├── composer/              <-- Mapeo de carga interna de Composer
│   ├── firebase/              <-- Código fuente de la librería PHP-JWT
│   └── autoload.php           <-- Archivo maestro de carga automatizada
│
├── index.html                 <-- Vista Principal Única (Single Page Application)
├── composer.json              <-- Manifiesto de dependencias de Composer
├── composer.lock              <-- Historial de versiones fijadas de librerías
└── .gitignore                 <-- Exclusión de archivos (como /vendor) para repositorios
7. DESCRIPCIÓN DETALLADA DE LOS ARCHIVOS Y FUNCIONES
📁 Capa de Modelo (/Modelo)
conexion.php
Encapsula el patrón de conexión mediante objetos PDO.
•	Clase: DB
•	Método conectar(): Retorna una instancia activa del objeto PDO. Configura el modo de errores en PDO::ERRMODE_EXCEPTION para capturar cualquier anomalía de red o sintaxis en bloques catch y fuerza el juego de caracteres a UTF8 para evitar incompatibilidades de tildes o caracteres especiales.
Producto.php
Contiene la abstracción de la entidad comercial Producto. Cada operación emplea sentencias preparadas.
•	Clase: Producto
•	Atributos: Privados, instanciados a través del constructor mediante inyección de la conexión PDO.
•	Método listar(): Ejecuta un SELECT * FROM productos. Retorna un arreglo asociativo mapeado con PDO::FETCH_ASSOC.
•	Método guardar($codigo, $producto, $precio, $cantidad): Recibe los datos sanitizados. Ejecuta un INSERT INTO productos (codigo, producto, precio, cantidad) VALUES (?, ?, ?, ?). Utiliza enlaces de parámetros implícitos para bloquear inyecciones SQL.
📁 Capa Controladora de Servicios (/api)
login.php
Endpoint responsable del proceso de autenticación.
•	Flujo Operativo: Captura el flujo de entrada crudo del servidor usando file_get_contents("php://input"), decodifica el JSON de la solicitud y realiza una consulta preparada buscando el string del usuario.
•	Validación Algorítmica: Evalúa las contraseñas cruzándolas con la función nativa password_verify().
•	Despacho del Token: Si la validación es afirmativa, inicializa el array $payload, define un tiempo de expiración (exp) equivalente al tiempo actual más 3600 segundos (1 hora) y codifica el token mediante el método estático JWT::encode(), usando una clave de cifrado simétrico robusta que supera los 256 bits de longitud obligatorios. Retorna un objeto JSON con la llave "success": true y la cadena del token.
productos.php
Controlador REST que centraliza y enruta las acciones del CRUD basándose en los estándares HTTP.
•	Flujo de Interceptación (Middleware): Antes de ejecutar cualquier instrucción SQL, lee los encabezados HTTP del servidor a través de apache_request_headers(). Si la cabecera Authorization no viene parametrizada o el método JWT::decode() arroja una excepción (por expiración o alteración de la firma), el script detiene la ejecución inmediatamente, inyecta un código de estado http_response_code(401) y responde con un JSON de acceso denegado.
•	Estructura Enrutadora: Una vez superado el filtro de seguridad, utiliza una estructura condicional switch($_SERVER['REQUEST_METHOD']):
o	Caso GET: Invoca el método listar() del modelo Producto y serializa la respuesta.
o	Caso POST: Decodifica los parámetros del JSON de entrada e invoca al método guardar() para agregar el registro en stock.
📁 Capa Cliente (/js & raíz)
index.html
Funciona bajo el concepto de SPA (Single Page Application). Aloja dos contenedores HTML principales mapeados con identificadores únicos (#seccion-login y #seccion-crud). La sección del CRUD cuenta con la clase CSS .oculto, manteniéndola invisible en la carga inicial para forzar el flujo lógico de seguridad.
script.js
Controlador maestro de la interfaz de usuario. Administra los ciclos de vida de la página mediante funciones asíncronas de alto nivel:
•	Función login(): Captura el texto de los inputs del formulario, arma un objeto estructurado y ejecuta una petición asíncrona hacia api/login.php especificando { method: 'POST' }. Si la API devuelve un estado exitoso, guarda la cadena string del token en el almacenamiento persistente con localStorage.setItem("jwt_token", data.token), oculta el formulario modificando las propiedades de las clases del DOM, remueve la clase .oculto del CRUD y ejecuta de forma encadenada la función de listado.
•	Función listarProductos(): Ejecuta un fetch hacia api/productos.php enviando en la configuración del objeto el encabezado obligatorio:
JavaScript
headers: { "Authorization": "Bearer " + localStorage.getItem("jwt_token") }
Al recibir el JSON de respuesta con los productos, limpia el contenedor del cuerpo de la tabla (#tabla.innerHTML = "") y mediante un bucle de repetición forEach inyecta las filas dinámicas (<tr><td>...) mapeando las variables dentro de template literals.
•	Función guardar(): Recopila las variables del nuevo producto, valida que no haya campos vacíos en la interfaz y envía una solicitud asíncrona mediante el método POST inyectando el token en la cabecera. Al recibir confirmación, dispara una alerta de éxito animada mediante Swal.fire() y vuelve a invocar a listarProductos() para refrescar la grilla en segundo plano.
•	Función cerrarSesion(): Elimina el token guardado usando localStorage.removeItem("jwt_token"), limpia la tabla y reestablece las clases CSS ocultando el CRUD y mostrando el Login.
8. IMPLEMENTACIÓN DE FETCH API Y FLUJO ASÍNCRONO
La API Fetch reemplaza los antiguos esquemas síncronos y los objetos XMLHttpRequest, proporcionando una sintaxis limpia basada en promesas nativas que optimizan el rendimiento de la red.
En este proyecto se aplicó el patrón moderno async/await, el cual permite escribir código asíncrono que se lee de forma secuencial, facilitando la captura de excepciones mediante bloques try/catch.
A continuación, se detalla la arquitectura exacta del flujo de comunicación cliente-servidor implementada en la aplicación:
Plaintext
  [ CLIENTE (Navegador) ]                      [ SERVIDOR (Apache/PHP) ]
    │                                              │
    ├── 1. POST /api/login.php ───────────────────>│ (Valida credenciales y
    │   (usuario/password)                         │  genera Token JWT)
    │                                              │
    <── 2. JSON {"success":true, "token":"..."} ───┤
    │                                              │
    ├── 3. Guarda token en LocalStorage            │
    │                                              │
    ├── 4. GET /api/productos.php ────────────────>│ (Middleware: Verifica Token)
    │   Header: Authorization: Bearer <token>      │ (Instancia Producto->listar())
    │                                              │
    <── 5. JSON Array de Productos [{}, {}] ───────┤
    │                                              │
    └── 6. Renderiza filas en la tabla             │
9. ESTRUCTURA COMPLETA Y DETALLADA DE UN JWT
El token JWT utilizado para proteger nuestra arquitectura RESTful se compone de tres bloques de texto independientes codificados en Base64 URL y separados por puntos (.):
1.	Header (Encabezado): Especifica el metadato del token. Indica de forma explícita el tipo de objeto (JWT) y el algoritmo de firma digital de clave simétrica utilizado (HS256).
2.	Payload (Carga Útil): Contiene las declaraciones (claims) del estado del usuario. Para este sistema se inyectan datos no sensibles de control: la identidad del operador (usuario) y la marca de tiempo de expiración Unix (exp), garantizando que la sesión sea revocada automáticamente al cumplir el tiempo límite en el servidor.
3.	Signature (Firma): El bloque más importante para la seguridad. Se calcula tomando el encabezado codificado, el payload codificado, una clave secreta custodiada en el servidor y aplicando el algoritmo de hash HMAC-SHA256.
$$Signature = HMACSHA256(Base64(Header) + "." + Base64(Payload), SecretKey)$$
Nota Crítica de Seguridad: Debido a los estándares internos de la librería Firebase\JWT, si la clave secreta configurada no cuenta con una longitud mínima de 256 bits (32 caracteres o letras), el motor de PHP detendrá el hilo del servicio arrojando una excepción de seguridad crítica ("Provided key is too short"), bloqueando los ataques de fuerza bruta por diccionario.
10. SEGURIDAD DETALLADA E INYECCIÓN SQL
Mitigación de Inyección SQL (Capa PDO)
La inserción directa de variables dentro de strings SQL ("SELECT * FROM x WHERE id = $id") fue erradicada por completo. El sistema implementa Consultas Preparadas.
Al utilizar los marcadores de posición de PDO (?), el motor de la base de datos compila la estructura de la consulta SQL de manera aislada antes de recibir los valores. Cuando los parámetros son inyectados, el motor los trata estrictamente como datos literales (strings o enteros) y nunca como instrucciones de código ejecutable, neutralizando cualquier intento de inyección de comandos SQL.
Protección de Credenciales (Capa Hashing)
Las contraseñas de acceso jamás se exponen en texto plano dentro de las tablas de almacenamiento. El sistema adopta la API de hashing de contraseñas nativa de PHP:
•	Almacenamiento (password_hash): Aplica el algoritmo robusto PASSWORD_BCRYPT, el cual genera una cadena irreversible de 60 caracteres que incluye una sal (salt) aleatoria integrada de forma automática, garantizando que dos usuarios con la misma contraseña tengan hashes totalmente diferentes.
•	Validación (password_verify): Compara el string introducido en el login contra el hash almacenado, calculando matemáticamente la coincidencia sin necesidad de descifrar la clave original.
11. MANUAL DE PRUEBAS EN POSTMAN (ENDPOINTS DE LA API)
Para comprobar el correcto desempeño de la API de manera aislada, se realizaron pruebas funcionales sobre los siguientes endpoints:
1. Autenticación de Operador
•	Método: POST
•	URL: http://localhost/CRUDJWT/api/login.php
•	Body (raw - JSON):
JSON
{
  "usuario": "admin",
  "password": "admin123"
}
•	Respuesta Esperada (200 OK):
JSON
{
  "success": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c3VhcmlvIjoiYWRtaW4iLC..."
}
2. Petición de Lectura (Sin Autorización - Prueba de Fallo)
•	Método: GET
•	URL: http://localhost/CRUDJWT/api/productos.php
•	Respuesta Esperada (401 Unauthorized):
JSON
{
  "success": false,
  "message": "Acceso denegado."
}
3. Petición de Lectura Autorizada (Prueba de Éxito)
•	Método: GET
•	URL: http://localhost/CRUDJWT/api/productos.php
•	Headers: Authorization $\rightarrow$ Bearer <pegar_token_obtenido_en_step_1>
•	Respuesta Esperada (200 OK):
JSON
[
  {
    "id": 1,
    "codigo": "P001",
    "producto": "Sandia",
    "precio": "2.50",
    "cantidad": 45
  }
]
4. Registro de Nuevo Producto
•	Método: POST
•	URL: http://localhost/CRUDJWT/api/productos.php
•	Headers: Authorization $\rightarrow$ Bearer <token>
•	Body (raw - JSON):
JSON
{
  "codigo": "P002",
  "producto": "Manzana",
  "precio": 1.20,
  "cantidad": 100
}
•	Respuesta Esperada (200 OK):
JSON
{
  "success": true
}
12. VALIDACIONES DE INTEGRIDAD
El sistema cuenta con un blindaje defensivo programado en dos frentes tácticos:
Plaintext
[ ENTRADA DE DATOS ] ──> [ CAPA CLIENTE: script.js ] ──> [ CAPA SERVIDOR: productos.php ] ──> [ BASE DE DATOS ]
                           - Control numérico positivo     - Token Válido y Vigente
                           - Bloqueo de campos vacíos       - Sanitización de Strings
                           - Prevención de submits nulos    - Try/Catch de Excepciones PDO
1.	Validaciones en el Cliente (Primera Línea de Defensa): El script de JavaScript intercepta el formulario evaluando que las longitudes no sean equivalentes a cero mediante .trim().length === 0, comprueba mediante funciones de conversión numérica que los valores de precio y cantidad correspondan a números reales positivos mayores a cero, y neutraliza el evento nativo del submit para controlar la transferencia a través de la red de forma segura.
2.	Validaciones en el Servidor (Filtro de Seguridad Absoluto): Debido a que las validaciones en el cliente pueden ser burladas alterando el código desde las herramientas de desarrollo del navegador, el servidor PHP realiza un re-chequeo obligatorio. Evalúa la presencia de parámetros usando operadores ternarios, valida la existencia de nulos, aplica filtros de sanitización a los strings para mitigar ataques XSS (Cross-Site Scripting) y controla los tipos de datos antes de enviarlos a las sentencias preparadas de la base de datos.
13. RESULTADOS OBTENIDOS
Al concluir las fases de desarrollo e integración, se obtuvo un software web robusto, desacoplado y funcional para la administración de productos. La interfaz se comporta de manera fluida y responsiva como una Single Page Application (SPA), eliminando por completo los parpadeos o recargas de página tradicionales.
La capa de seguridad basada en Tokens JWT se acopló perfectamente con la arquitectura de clases orientadas a objetos, demostrando que los endpoints de consulta y escritura están completamente protegidos ante peticiones no autorizadas de terceros o herramientas externas. Las pruebas cruzadas ejecutadas en la consola del desarrollador y en la interfaz de Postman arrojaron resultados limpios, confirmando la correcta sincronización de datos en formato JSON y el manejo semántico de los códigos de estado del protocolo HTTP.
<img width="300" height="300" alt="image" src="https://github.com/user-attachments/assets/110a6173-2581-42a3-b470-1e9cafa9321e" />
<img width="300" height="300" alt="image" src="https://github.com/user-attachments/assets/1f8aa469-ef1c-4c4d-9418-6ca7668c26df" />


14. CONCLUSIONES
•	El consumo de servicios mediante Fetch API optimiza la experiencia de usuario final en sistemas de gestión corporativos, reduciendo el consumo de ancho de banda al transferir cadenas JSON compactas en lugar de páginas HTML completas.
•	La Programación Orientada a Objetos (POO) en PHP dota al código del backend de alta modularidad, escalabilidad y legibilidad, facilitando la mantenibilidad a largo plazo al aislar los componentes lógicos en archivos de responsabilidad única.
•	El uso de la capa de abstracción PDO combinado con consultas preparadas constituye el estándar de oro actual en el desarrollo backend para anular de raíz ataques críticos de Inyección SQL.
•	La autenticación basada en JWT (JSON Web Tokens) demuestra ser un mecanismo idóneo para entornos modernos, ya que permite al servidor validar la identidad del usuario de manera matemática sin necesidad de almacenar sesiones físicas o registros temporales en memoria, agilizando el rendimiento de la infraestructura.
•	El uso de herramientas de pruebas automatizadas como Postman resulta fundamental en la formación académica y profesional, permitiendo auditar la seguridad y el comportamiento exacto de los servicios REST de forma aislada antes de acoplarlos al diseño visual de la interfaz.
•	La correcta implementación de funciones criptográficas como password_hash() resguarda la privacidad y confidencialidad de la base de usuarios, alineando el software escolar con las normativas actuales de seguridad informática internacional.
