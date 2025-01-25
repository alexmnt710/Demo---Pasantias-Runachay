<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Word</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .custom-file-upload {
            display: block;
            width: 100%;
            padding: 10px;
            border: 2px dashed #007bff;
            text-align: center;
            cursor: pointer;
            font-weight: bold;
            color: #007bff;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .custom-file-upload:hover {
            background: #007bff;
            color: #fff;
        }
        .table {
            margin-top: 20px;
            background: #ffffff;
        }
        .table thead {
            background: #007bff;
            color: white;
        }
        .table tbody tr:hover {
            background: #f1f1f1;
        }
        .btn {
            transition: all 0.3s ease;
        }
        .btn-info {
            background: #17a2b8;
            border: none;
        }
        .btn-success {
            background: #28a745;
            border: none;
        }
        .btn-info:hover, .btn-success:hover {
            filter: brightness(90%);
        }
        .icon-large {
            font-size: 1.3em;
        }
    </style>
</head>
<body>
    <div id="app" class="container">
        <h1><i class="glyphicon glyphicon-upload"></i> Subir Plantilla</h1>
        
        <label for="file-upload" class="custom-file-upload">
            <i class="glyphicon glyphicon-file icon-large"></i> Seleccionar Archivo
        </label>
        <input type="file" id="file-upload" @change="handleFileUpload" accept=".doc,.docx" class="form-control" style="display: none;">
        
        <table class="table table-bordered table-hover" v-if="users.length > 0">
            <thead>
                <tr>
                    <th><i class="glyphicon glyphicon-user"></i> Nombre</th>
                    <th><i class="glyphicon glyphicon-info-sign"></i> Información</th>
                    <th><i class="glyphicon glyphicon-cog"></i> Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>@{{ user.name }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" @click="showInfo(user)">
                            <i class="glyphicon glyphicon-info-sign"></i> Ver Info
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm" @click="setData(user)">
                            <i class="glyphicon glyphicon-check"></i> Aplicar Datos
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                users: [],
                uploadedTemplate: null, // Aquí guardamos el archivo subido
                selectedUser: {
                    name: '',
                    DNI: '',
                    grade: '',
                    period: '',
                    parent: {
                        name: '',
                        DNI: '',
                        signature: ''
                    },
                    institution: {
                        full_name: '',
                        sigla_name: '',
                        owner_name: '',
                        owner_DNI: '',
                        co_owner_name: '',
                        co_owner_DNI: ''
                    },
                    contract: {
                        city: '',
                        day_today: '',
                        date_today: ''
                    }
                }
            },
            methods: {
                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.uploadedTemplate = file; // Guardamos la referencia al archivo subido
                        this.users = [
                            {
                                id: 1,
                                name: "John Doe",
                                DNI: "1234567890",
                                grade: "Bachillerato",
                                period: "2025-2026",
                                parent: {
                                    name: "Jane Doe",
                                    DNI: "0987654321",
                                    signature: "Firma de Jane Doe"
                                },
                                institution: {
                                    full_name: "Institución Educativa Nueva Pacto",
                                    sigla_name: "IENP",
                                    owner_name: "Lcda. María Pérez",
                                    owner_DNI: "1122334455",
                                    co_owner_name: "Dr. Carlos Gómez",
                                    co_owner_DNI: "5566778899"
                                },
                                contract: {
                                    city: "Quito",
                                    day_today: "Lunes",
                                    date_today: "21 de Enero de 2025"
                                }
                            },
                            {
                                id: 2,
                                name: "Michael Smith",
                                DNI: "9876543210",
                                grade: "Secundaria",
                                period: "2025-2026",
                                parent: {
                                    name: "Laura Smith",
                                    DNI: "8765432109",
                                    signature: "Firma de Laura Smith"
                                },
                                institution: {
                                    full_name: "Colegio Internacional",
                                    sigla_name: "CINT",
                                    owner_name: "Dr. Roberto Martínez",
                                    owner_DNI: "2233445566",
                                    co_owner_name: "Lcda. Andrea López",
                                    co_owner_DNI: "6655443322"
                                },
                                contract: {
                                    city: "Guayaquil",
                                    day_today: "22",
                                    date_today: "22 de Enero de 2025"
                                }
                            }
                        ];
                    }
                },
                showInfo(user) {
                    Swal.fire({
                        title: `Información de ${user.name}`,
                        html: `
                            <p><strong>DNI:</strong> ${user.DNI}</p>
                            <p><strong>Grado:</strong> ${user.grade}</p>
                            <p><strong>Periodo:</strong> ${user.period}</p>
                            <hr>
                            <h4>Representante Legal</h4>
                            <p><strong>Nombre:</strong> ${user.parent.name}</p>
                            <p><strong>DNI:</strong> ${user.parent.DNI}</p>
                            <p><strong>Firma:</strong> ${user.parent.signature}</p>
                            <hr>
                            <h4>Institución</h4>
                            <p><strong>Nombre:</strong> ${user.institution.full_name}</p>
                            <p><strong>Siglas:</strong> ${user.institution.sigla_name}</p>
                            <p><strong>Dueño:</strong> ${user.institution.owner_name} (DNI: ${user.institution.owner_DNI})</p>
                            <p><strong>Co-Dueño:</strong> ${user.institution.co_owner_name} (DNI: ${user.institution.co_owner_DNI})</p>
                            <p><strong>Ciudad:</strong> ${user.contract.city}</p>
                            <p><strong>Fecha:</strong> ${user.contract.date_today}</p>
                        `,
                        icon: "info",
                        confirmButtonText: "Cerrar"
                    });
                },
                async setData(user) {
                    if (!this.uploadedTemplate) {
                        Swal.fire("Error", "Por favor, sube una plantilla antes de asignar datos.", "error");
                        return;
                    }

                    this.loadingModal();
                    console.log("Usuario seleccionado:", user);
                    console.log("Plantilla cargada:", this.uploadedTemplate.name);

                    //se apunta a la ruta 
                    const formData = new FormData();
                    formData.append("template", this.uploadedTemplate);
                    formData.append("user", JSON.stringify(user));

                    // se hace el fetch
                    const response = await fetch("upload", {
                        method: "POST",
                        body: formData
                    })
                    const data = await response.json();
                    this.closeModal();

                },
                //modal de sweet alert de apertura y cierre de carga
                loadingModal() {
                    Swal.fire({
                        title: "Cargando...",
                        html: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div> Por favor, espere un momento.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                closeModal() {
                    Swal.close();
                }


            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
