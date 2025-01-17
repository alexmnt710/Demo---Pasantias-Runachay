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
            border: 5px solid #ccc;
            background-color: #f9f9f9;
            border-radius: 0px;
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
        .file-container {
            display: flex;
            justify-content: center; 
            align-items: center; 
            padding: 10px;
            width: 100%; 
            max-width: 300px; 
            margin: 0 auto; 
        }
        .input-group {
            display: flex;
            align-items: center;
            gap: 10px; 
            border-radius: 5px;
            padding: 5px;
            max-width: 400px; 
        }

        .input-group label {
            font-weight: bold;
            white-space: nowrap; 
        }

        .input-group input {
            flex: 1;
            padding: 8px;
            width: 150px !important;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .input-group select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-number::-webkit-inner-spin-button,
        .form-number::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }
        .mb-10{
            margin-bottom: 10px;
        }
        .mt-10{
            margin-top: 10px;
        }
	</style>
</head>
<body>  
        <h1 class="mb-10" >Demo de Whatsapp</h1>
        <div id="app">
            <div class="container container_edit  " >
                <form action="send-mensage-whs"  method="POST" enctype="multipart/form-data" @submit="concatenarNumero">
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <!-- seleccion del numero -->
                            <div class="input-group">
                                <select v-model="codigoSeleccionado" class="selector-pais">
                                    <option v-for="pais in paises" :value="pais.codigo">
                                        @{{ pais.bandera }} @{{ pais.nombre }} (@{{ pais.codigo }})
                                    </option>
                                </select>
                                <label for="exampleInputName2">Numero:</label>
                                <input type="number" v-model="numero" min="1" pattern="\d{1,9}" maxlength="9" @input="validarMaximo"  class="form-number">
                            </div>
                            <!-- seleccion de un input -->
                            <div >
                                <div class=" flex-justify-content-start ms-10" >
                                    <label class="edit_checkbox ">
                                        <input type="checkbox" v-model="opciones.imagen">
                                        <div>
                                            <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                                            Imagen
                                        </div>
                                    </label>
                                    <div class="file-container">
                                            <input v-if="opciones.imagen" type="file" v-model="link" @change="subirDocumento" placeholder="Ingrese el link" >
                                    </div>
                                </div>

                                <div class="flex-justify-content-start ms-10" >
                                    <label class="edit_checkbox" >
                                        <input type="checkbox" v-model="opciones.texto">
                                        <div>
                                            <span class="glyphicon glyphicon-italic" aria-hidden="true"></span>
                                            Texto
                                        </div>
                                    </label>
                                </div>

                                <div class="flex-justify-content-start ms-10">
                                    <label class="edit_checkbox" >
                                        <input type="checkbox" v-model="opciones.mensajeLink"> 
                                        <div>
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            Mensaje con Link
                                        </div>
                                    </label>
                                    <div class="file-container">
                                        <input v-if="opciones.mensajeLink" type="text" v-model="link" @change="subirLink" placeholder="Ingrese el link" class="extra-input">
                                    </div>
                                </div>

                                <div class="flex-justify-content-start ms-10">
                                    <label class="edit_checkbox" >
                                        <input type="checkbox" v-model="opciones.mensajeDocumento">
                                        <div>
                                            <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>
                                            Mensaje con Documento
                                        </div>
                                    </label>
                                    <div class="file-container">
                                    <input v-if="opciones.mensajeDocumento" type="file" @change="subirDocumento" class="extra-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 col-md-4">
                            <div class="mt-10">
                                <p>Numero:  + @{{ numeroCompleto }} </p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-default">
                        Enviar
                        <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>

                    </button>
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
                codigoSeleccionado: '+593',
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
                subirLink(event) {
                    this.link = event.target.value;
                },
                concatenarNumero() {
                    document.querySelector('input[name="telefono"]').value = this.numeroCompleto;
                },
                validarMaximo() {
                    if (this.numero.length > 9) {
                        this.numero = this.numero.slice(0, 9);
                    }
                }
            }
        });

</script>

