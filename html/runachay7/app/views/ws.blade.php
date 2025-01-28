<!doctype html>
<head>

    <meta charset="UTF-8">
    <title>Demo de Whatsapp</title>
    <!-- librerias a usar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- vue 2.6 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.0/vue.common.dev.js"></script>
    <!-- axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
     <!-- fin de las librerias a usar -->
    <style>
        body {
                margin:0;
                font-family:'Lato', sans-serif;
                text-align:center;
                color: #999;
                background-color: #faf3e0; /* Crema */

        }
        .p-2{
            margin-bottom: 10px;
        }
    </style>
</head>
<body>  
    <div id="app">

        <button @click="obtenerQR" class="btn btn-primary" type="submit">Generar Qr</button>
        <div class="container-fluid">
                <div v-if="qrUrl" class="qr-container">
                    <img :src="qrUrl" alt="QR de WhatsApp" class="qr-image">
                </div>
                <p v-if="error" class="text-danger">@{{ error }}</p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" v-model="nuevoUsuario.nombre" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Código Internacional:</label>
                        <select v-model="nuevoUsuario.codigo" class="form-control">
                            <option v-for="pais in paises" :value="pais.codigo">
                                @{{ pais.bandera }} @{{ pais.nombre }} (@{{ pais.codigo }})
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Número Completo:</label>
                        <input type="text" v-model="nuevoUsuario.numero" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <!-- tabla de usuarios  -->
        <h2 class="text-center">Tabla de Usuarios</h2>
        <div class="container-fluid">
            <button @click="agregarUsuario " class="btn btn-primary p-2 ">Agregar Usuario</button>
            </button>
            <button class="btn btn-primary p-2" data-toggle="modal" data-target="#mensajeModal">
                Enviar Mensaje
            </button>
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Código Internacional</th>
                        <th>Número Completo</th>
                        <th>Seleccionado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(usuario, index) in usuarios" :key="index">
                        <td>@{{ index + 1 }}</td>
                        <td>@{{ usuario.nombre }}</td>
                        <td>+@{{ usuario.codigo }}</td>
                        <td>@{{ usuario.numero }}</td>
                        <td><input type="checkbox" v-model="usuario.seleccionado"></td>
                    </tr>
                </tbody>
            </table>


















                <div id="mensajeModal" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Enviar Mensaje</h4>
                            </div>
                            <div class="modal-body">
                                <!-- Botones para seleccionar el tipo de mensaje -->
                                <div class="btn-group">
                                    <button class="btn btn-default" :class="{ 'btn-primary': tipoMensaje === 'texto' }" @click="tipoMensaje = 'texto'">Enviar Texto</button>
                                    <button class="btn btn-default" :class="{ 'btn-primary': tipoMensaje === 'imagen' }" @click="tipoMensaje = 'imagen'">Enviar Imagen</button>
                                </div>

                                <!-- Inputs dinámicos según la opción seleccionada -->
                                <div v-if="tipoMensaje === 'texto'" class="form-group">
                                    <label>Mensaje de Texto:</label>
                                    <textarea v-model="mensajeTexto" class="form-control"></textarea>
                                </div>

                                <div v-if="tipoMensaje === 'imagen'" class="form-group">
                                    <label>Mensaje con Imagen:</label>
                                    <input  type="text" v-model="mensajeTexto" placeholder="Escribe un mensaje" class="form-control p-2">
                                    <input type="text" v-model="imagenUrl" placeholder="https://ejemplo.com/imagen.png" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success" @click="enviarMensajes">Enviar</button>
                                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div> 
    </div>
    
</body>
</html>
<script>
    new Vue({
        el: '#app',
        data: {
            message: 'Escanea el QR para conectar WhatsApp',
            qrUrl: '',
            error: '',
            //para los nuevos usuarios
            nuevoUsuario: { nombre: '', codigo: '', numero: '' },
            usuarios: [
            ],
            paises: [
                { nombre: "Ecuador", codigo: "593", bandera: "🇪🇨" },
                { nombre: "México", codigo: "52", bandera: "🇲🇽" },
                { nombre: "España", codigo: "34", bandera: "🇪🇸" },
                { nombre: "Colombia", codigo: "57", bandera: "🇨🇴" },
                { nombre: "Argentina", codigo: "54", bandera: "🇦🇷" },
                { nombre: "Perú", codigo: "51", bandera: "🇵🇪" },
                { nombre: "Brasil", codigo: "55", bandera: "🇧🇷" },
                { nombre: "Francia", codigo: "33", bandera: "🇫🇷" }
            ],
            //modal
            tipoMensaje: 'texto', // 'texto' por defecto
            mensajeTexto: '',
            imagenSeleccionada: null,
            apiUrl: 'http://localhost:3000/whatsapp',
            imagenUrl: '',
        },
        methods: {
            agregarUsuario(){
                if (!this.nuevoUsuario.nombre || !this.nuevoUsuario.codigo || !this.nuevoUsuario.numero) {
                    alert('Todos los campos son obligatorios');
                    return;
                }

                this.usuarios.push({
                    nombre: this.nuevoUsuario.nombre,
                    codigo: this.nuevoUsuario.codigo,
                    numero: this.nuevoUsuario.numero,
                    seleccionado: false
                });

                this.nuevoUsuario = { nombre: '', codigo: '', numero: '' };
            },
            async obtenerQR() {
                try {
                    const response = await axios.get(`${this.apiUrl}/qr`, { responseType: 'blob' });

                    // Crear URL del Blob para mostrar la imagen
                    this.qrUrl = URL.createObjectURL(response.data);
                    this.error = '';
                } catch (err) {
                    this.error = 'Error obteniendo el QR. Verifica que el backend está corriendo.';
                    console.error('Error al obtener QR:', err);
                }
            },
            //funciones de enviar
            cargarImagen(event) {
                    const archivo = event.target.files[0];
                    if (archivo) {
                        this.imagenSeleccionada = archivo.name;
                    }
            },
            async enviarMensajes() {
                const usuariosSeleccionados = this.usuarios.filter(usuario => usuario.seleccionado);
                if (usuariosSeleccionados.length === 0) {
                    alert('No hay usuarios seleccionados.');
                    return;
                }
                for (const usuario of usuariosSeleccionados) {
                    console.log(usuario);
                    await this.enviarMensaje(usuario);
                }
                alert('Mensajes enviados correctamente.');
            },
            async enviarMensaje(usuario) {
                try {
                    const telefono = usuario.codigo.replace('+', '') + usuario.numero;
                    let payload = { phone: telefono };
                    let endpoint = '';

                    if (this.tipoMensaje === 'texto') {
                        payload.message = this.mensajeTexto;
                        endpoint = `${this.apiUrl}/send`;
                    } else if (this.tipoMensaje === 'imagen') {
                        if (!this.imagenUrl) {
                            alert('Por favor, ingresa una URL de imagen.');
                            return;
                        }
                        payload.imageUrl = this.imagenUrl;
                        payload.caption = this.mensajeTexto;
                        endpoint = `${this.apiUrl}/send-image`;
                    }

                    const response = await axios.post(endpoint, payload, {
                        headers: { 'Content-Type': 'application/json' }
                    });
                } catch (error) {
                    console.error(`Error enviando mensaje a ${usuario.nombre}:`, error);

                }
            }

        }
    });

</script>
