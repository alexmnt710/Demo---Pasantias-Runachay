<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.0/vue.common.dev.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- libreria para los numeros internacionales -->

    <style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			margin:0;
			font-family:'Lato', sans-serif;
			text-align:center;
			color: #999;
            background-color: #faf3e0; /* Crema */

		}

		.welcome {
			width: 300px;
			height: 200px;
			position: absolute;
			left: 50%;
			top: 50%;
			margin-left: -150px;
			margin-top: -100px;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 16px 0 0 0;
		}
        .border_r{
            border: 5px solid red;
        }
        .border_y{
            border: 5px solid green;
        }
        .border_g{
            border: 5px solid yellow;
        }
        .container_edit{
            padding: 10px;
        }
        .edit_checkbox{
            margin-top: 10px;
            margin-right: 10px;
            padding:10px;
            border: 1px solid #ccc;
            border-radius: 15px;
            background-color: #f9f9f9;
        }
        .flex-justify-content-end{
            display: flex;
            justify-content: flex-end;
        }
        .flex-justify-content-start{
            display: flex;
            justify-content: start;
        }
        .ms-10{
            margin: 10px;
        }
	</style>
</head>
<body>  
        <h1>Demo de Whatsapp</h1>
        <div id="app">
            <div class="container container_edit border_r " >
                <form action="send-mensage-whs"  method="POST" enctype="multipart/form-data" @submit="concatenarNumero">
                    <div class="row">
                        <div class="col-xs-6 col-md-4">
                            <!-- seleccion del numero -->
                            <div>
                                Numero: <input type="number" v-model="numero" min="1"  step="1" class="input-numero">
                                <select v-model="codigoSeleccionado" class="selector-pais">
                                    <option v-for="pais in paises" :value="pais.codigo">
                                        @{{ pais.bandera }} @{{ pais.nombre }} (@{{ pais.codigo }})
                                    </option>
                                </select>
                                <input type="hidden" name="telefono" :value="numeroCompleto">
                            </div>
                            <!-- seleccion de un input -->
                            <div class="border_y">
                                <div class="border_b flex-justify-content-start ms-10" >
                                    <label class="edit_checkbox ">
                                        <input type="checkbox" v-model="opciones.imagen">
                                        <div>
                                            <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                                            Imagen
                                        </div>
                                    </label>
                                </div>
                                <label>
                                    <input type="checkbox" v-model="opciones.texto"> Texto
                                </label>
                                <label>
                                    <input type="checkbox" v-model="opciones.mensajeLink"> Mensaje con Link
                                </label>
                                <input v-if="opciones.mensajeLink" type="text" v-model="link" placeholder="Ingrese el link" class="extra-input">
                                <label>
                                    <input type="checkbox" v-model="opciones.mensajeDocumento"> Mensaje con Documento
                                </label>
                                <input v-if="opciones.mensajeDocumento" type="file" @change="subirDocumento" class="extra-input">
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4">
                            <p>Numero:  + @{{ numeroCompleto }} </p>
                        </div>
                    </div>
                    <button type="submit" class="btn-enviar">Enviar</button>
                </form>
            </div>
        </div>
		@if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
</body>
</html>
<script>
        new Vue({
            el: '#app',
            data: {
                message: 'Hola, Vue 2.6 en Laravel Blade!',
                numero:'',
                codigoSeleccionado: '+34',
                opciones:{
                    imagen: false,
                    texto: false,
                    mensajeLink: false,
                    mensajeDocumento: false
                },
                link: "",  
                documento: "", 
                paises: [
                    { nombre: "Estados Unidos", codigo: "+1", bandera: "🇺🇸" },
                    { nombre: "Reino Unido", codigo: "+44", bandera: "🇬🇧" },
                    { nombre: "España", codigo: "+34", bandera: "🇪🇸" },
                    { nombre: "México", codigo: "+52", bandera: "🇲🇽" },
                    { nombre: "Colombia", codigo: "+57", bandera: "🇨🇴" },
                    { nombre: "Argentina", codigo: "+54", bandera: "🇦🇷" },
                    { nombre: "Perú", codigo: "+51", bandera: "🇵🇪" },
                    { nombre: "Ecuador", codigo: "+593", bandera: "🇪🇨" },
                    { nombre: "Brasil", codigo: "+55", bandera: "🇧🇷" },
                    { nombre: "Francia", codigo: "+33", bandera: "🇫🇷" },
                    { nombre: "Alemania", codigo: "+49", bandera: "🇩🇪" },
                    { nombre: "Japón", codigo: "+81", bandera: "🇯🇵" },
                    { nombre: "India", codigo: "+91", bandera: "🇮🇳" }
                ]
            },
            computed: {
                numeroCompleto() {
                    console.log(this.codigoSeleccionado + this.numero);
                    return this.codigoSeleccionado.replace("+", "") + this.numero;
                }
            },
            methods:{
                subirDocumento(event) {
                    this.documento = event.target.files[0] ? event.target.files[0].name : "";
                },
                concatenarNumero() {
                    document.querySelector('input[name="telefono"]').value = this.numeroCompleto;
                }
            }
        });

</script>

