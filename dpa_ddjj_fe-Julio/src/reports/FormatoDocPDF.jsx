
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
import { useState } from 'react'
import logoUmssBase64 from './logoUmssBase64';

//pdfMake.vfs = pdfFonts.pdfMake.vfs


function FormatoDocPDF({ nombre, fecha, usuario, categoria, categorias, instituciones, guardar }) {

    const letras = 'BCDEFGHIJKLMNOPQRSTUVWXYZ'
    let auxInstitucion = []

        auxInstitucion = [...instituciones]
        let tipoR = auxInstitucion.filter(institucion => institucion.tipo === 'R').length
        for (let i = 1; i > tipoR; i--) {
            auxInstitucion.push(
                {
                    nombre:"",
                    cargo:"",
                    total_ganado:null,
                    tipo:"R"
                }
            )
        }
        let tipoI = auxInstitucion.filter(institucion => institucion.tipo === 'I').length
        for (let i = 2; i > tipoI; i--) {
            auxInstitucion.push(
                {
                    nombre:"",
                    cargo:"",
                    total_ganado:null,
                    tipo:"I"
                }
            )
        }


    /**
     * funcion que genera una lista de listas de objetos que representa cada fila de la tabla categoria
     * @returns array de items categorias
     */
    function generarTablaCategoria() {
        let listaObj = []
        listaObj.push([
            {
                border: [true, true, true, true],
                text: 'CATEGORIA',
                bold: true,
            },
            {
                border: [true, true, true, true],
                text: 'REMUNERACIÓN (EN Bs.)',
                bold: true,
            },
        ])

        categorias.forEach(element => {
            let cat = categoria.filter(aux => aux.id_categoria == element.id_categoria)
            listaObj.push([
                {
                    border: [true, true, true, true],
                    text: element.nombre,
                },
                {
                    border: [true, true, true, true],
                    text: (Math.round(cat[0].remuneracion * 100) / 100).toFixed(2),
                    alignment: 'center'
                },
            ])
        });

        listaObj.push([
            {
                border: [true, true, true, true],
                text: 'TOTAL',
                bold: true,
                alignment: 'right'
            },
            {
                border: [true, true, true, true],
                text: totalRemuneracionesCategoria(),
                alignment: 'center'
            },
        ])
        return listaObj;
    }

    function totalRemuneracionesCategoria() {
        let suma = 0;
        categoria.forEach(element => {
            suma += element.remuneracion
        });
        return (Math.round(suma * 100) / 100).toFixed(2)
    }

    function totalRemuneracionInstituciones() {
        let suma = 0;
        instituciones.forEach(element => {
            suma += parseFloat(element.total_ganado)
        });
        return (Math.round(suma * 100) / 100).toFixed(2)
    }

    var docDefinition = {
        content: [
            {
                style: 'tableExample',

                table: {
                    widths: ['auto', '*'],
                    body: [
                        [
                            {
                                rowSpan: 4,
                                image: 'data:image/png;base64,'+logoUmssBase64,
                                width: 80
                            },
                            { text: 'FORM-DCA-FORM-08', style: 'header', }
                        ],
                        [
                            '',
                            { text: 'UNIVERSIDAD MAYOR DE SAN SIMON', style: 'header' },
                        ],
                        [
                            '',
                            { text: 'DIRECCION DE PLANIFICACION ACADEMICA', style: 'headerdos' }
                        ],
                        [
                            '',
                            { text: 'DEPARTAMENTO DE PLANIFICACION ACADEMICA', style: 'headerdos' },
                        ],
                    ]
                },
                layout: {
                    defaultBorder: false,
                }
            },

            /**
             * Tabla de datos del usuario
             **/

            {
                style: 'tableExample',

                table: {
                    widths: ['auto', 'auto', '*', 'auto', 'auto'],
                    body: [
                        [
                            {
                                colSpan: 3,
                                border: [false, false, false, false],
                                text: ""
                            },
                            '',
                            '',
                            {
                                border: [false, false, false, false],
                                bold: true,
                                alignment: 'right',
                                text: 'Fecha de llenado:'
                            },
                            {
                                border: [true, true, true, true],
                                alignment: 'center',
                                text: fecha.fecha
                            }
                        ],
                        [
                            {
                                border: [false, false, false, false],
                                bold: true,
                                alignment: 'right',
                                text: 'Yo:'
                            },
                            {
                                border: [true, true, false, true],
                                text: ""
                            },
                            {
                                colSpan: 2,
                                border: [false, true, false, true],
                                alignment: 'center',
                                text: `${usuario.nombre1} ${usuario.nombre2}  ${usuario.paterno}  ${usuario.materno}`
                            },
                            '',
                            {
                                border: [false, true, true, true],
                                text: ''
                            },
                        ],
                        [
                            {
                                border: [false, false, false, false],
                                bold: true,
                                alignment: 'right',
                                text: 'con CI:'
                            },
                            {
                                border: [true, true, true, true],
                                alignment: 'center',
                                text: usuario.ci
                            },
                            {
                                border: [false, false, false, false],
                                text: ''
                            },
                            '',
                            ''
                        ],
                    ]
                },
                layout: {
                    defaultBorder: false,
                }
            },
            {
                style: 'tableExample',
                bold: true,
                table: {
                    widths: ['auto', '*', 'auto', 'auto'],
                    body: [
                        [
                            {
                                border: [false, false, false, false],
                                text: 'DECLARO PERCIBIR EN EL MES DE:'
                            },
                            {
                                border: [false, false, false, false],
                                text: fecha.mes
                            },
                            {
                                border: [false, false, false, false],
                                text: 'DE LA GESTIÓN:'
                            },
                            {
                                border: [true, true, true, true],
                                text: fecha.gestion
                            },
                        ],
                    ]
                },
                layout: {
                    defaultBorder: false,
                }
            },
            {
                text: 'LAS SIGUIENTES REMUNERACIONES EN EL SECTOR PÚBLICO:',
                style: 'subheader',
                alignment: 'center'
            },
            /**
             * Tabla a formulario de categorias
             */
            {
                text: 'A.- UNIVERSIDAD MAYOR DE SAN SIMÓN',
                style: 'subheader',
                alignment: 'left'
            },
            {
                style: 'tableExample',
                table: {
                    widths: ['*', 'auto'],
                    body: generarTablaCategoria()
                },
                layout: {
                    defaultBorder: false,
                }
            },
            /**
             * nota de la tabla a
             */
            {
                text: 'IMPORTANTE: En el caso de LOS ITEMS 1 y 2 (docentes y administrativos) se considera el total de ganado mensual independientemente del tipo de contrato (indefinido, plazo fijo o servicios). en el caso de los servicios comprendidos del ITEM 3 AL ITEM 9 se considera la remuneracion de las fechas que se ha efectuado el servicio sin considerar si este ya ha sido pagado en efectivo o no. No debe incluirse el servicio para el cual se firma la presente declaración.',
                style: 'paragraf'
            },


            /**
             * tabla de las instituciones publicas
             */

            auxInstitucion.filter(institucion => institucion.tipo === 'I').map((item, index) => (
                {
                    style: 'tableExample',
                    table: {
                        widths: ['auto', 50, '*', 'auto', 50],
                        body: [
                            [
                                {
                                    colSpan: 5,
                                    border: [false, false, false, false],
                                    text: `${letras[index]}.- OTRA INSTITUCIÓN PÚBLICA`,
                                    style: 'subheader',
                                    alignment: 'left'
                                },
                                '',
                                '',
                                '',
                                ''
                            ],
                            [
                                {
                                    border: [true, true, false, false],
                                    text: 'Nombre de la institucion publica:'
                                },
                                {
                                    border: [false, true, true, false],
                                    text: ''
                                },
                                {
                                    colSpan: 3,
                                    border: [true, true, true, true],
                                    text: item.nombre
                                },
                                '',
                                ''
                            ],
                            [
                                {
                                    border: [true, false, false, true],
                                    text: ''
                                },
                                {
                                    border: [false, false, true, true],
                                    text: 'Cargo: '
                                },
                                {
                                    border: [true, true, true, true],
                                    text: item.cargo
                                },
                                {
                                    border: [true, true, true, true],
                                    text: 'Total Ganado (Bs.):'
                                },
                                {
                                    border: [true, true, true, true],
                                    text: (Math.round(item.total_ganado * 100) / 100).toFixed(2),
                                    alignment: 'right'
                                },
                            ],
                        ]
                    },
                    layout: {
                        defaultBorder: false,
                    }
                }
            )),

            /**
             * Tabla de renta de jubilaciones
             */
            auxInstitucion.filter(institucion => institucion.tipo === 'R').map((institucion, index) => (
                {
                    style: 'tableExample',
                    table: {
                        widths: ['auto', 50, '*', 'auto', 50],
                        body: [
                            [
                                {
                                    colSpan: 5,
                                    border: [false, false, false, false],
                                    text: `${letras[instituciones.length-instituciones.filter(institucion => institucion.tipo === 'R').length+index]}.- RENTA DE JUBILACIÖN`,
                                    style: 'subheader',
                                    alignment: 'left'
                                },
                                '',
                                '',
                                '',
                                ''
                            ],
                            [
                                {
                                    border: [true, true, false, false],
                                    text: 'Nombre de la institucion publica:'
                                },
                                {
                                    border: [false, true, true, false],
                                    text: ''
                                },
                                {
                                    colSpan: 3,
                                    border: [true, true, true, true],
                                    text: institucion.nombre
                                },
                                '',
                                ''
                            ],
                            [
                                {
                                    border: [true, false, false, true],
                                    text: ''
                                },
                                {
                                    border: [false, false, true, true],
                                    text: 'Cargo: '
                                },
                                {
                                    border: [true, true, true, true],
                                    text: institucion.cargo
                                },
                                {
                                    border: [true, true, true, true],
                                    text: 'Total (Bs.):',
                                    alignment: 'right'
                                },
                                {
                                    border: [true, true, true, true],
                                    text: (Math.round(institucion.total_ganado * 100) / 100).toFixed(2),
                                    alignment: 'right'
                                },
                            ],

                        ]
                    },
                    layout: {
                        defaultBorder: false,
                    }
                }
            )),
            /**
             * tabla sumatoria del total ganado en instituciones publicas
             */
            {
                style: 'tableExample',
                table: {
                    widths: ['*', '*', '*', '*', '*', 50],
                    heights: ['auto', 'auto'],
                    body: [
                        [
                            {
                                colSpan: 3,
                                border: [true, true, false, false],
                                text: 'TOTAL GANADO EN INSTITUCIONES PUBLICAS'
                            },
                            '',
                            '',
                            {
                                colSpan: 3,
                                border: [false, true, true, false],
                                text: ''
                            },
                            '',
                            ''
                        ],
                        [
                            {
                                colSpan: 2,
                                border: [true, false, false, true],
                                text: ''
                            },
                            '',
                            {
                                colSpan: 3,
                                border: [false, false, false, true],
                                text: 'TOTAL REMUNERACIONES DEL SECTOR PUBLICO:'
                            },
                            '',
                            '',
                            {
                                border: [true, true, true, true],
                                text: totalRemuneracionInstituciones(),
                                alignment: 'right'
                            },
                        ],

                    ]
                },
                layout: {
                    defaultBorder: false,
                }
            },
            /**
             * nota de la tabla d
             */
            {
                margin: [0, 0, 0, 10],
                text: 'NOTA.- El monto declarado corresponde al total ganado (no al liquido pagable), es decir al monto percibido antes de deduciones, aportes laborales e impuestos,. Se considera institución pública a toda aquella que se encuentra comprendida en los Artículos 3, 4 y 5 de la Ley 1178 del 20 de julio de 1990.',
                style: 'paragraf',
            },
            /**
             * Tabla firma del docente
             */
            {
                style: 'tableExample',
                table: {
                    widths: ['*', '*', '*', 'auto', 'auto'],
                    heights: [30],
                    body: [
                        [
                            {
                                border: [true, true, true, true],
                                text: 'FIRMA: '
                            },
                            {
                                colSpan: 2,
                                border: [true, true, true, true],
                                text: ''
                            },
                            '',
                            {
                                border: [true, true, true, true],
                                text: 'ACLARACIÓN DE LA FIRMA:'
                            },
                            {
                                border: [true, true, true, true],
                                text: `${usuario.nombre1} ${usuario.nombre2}  ${usuario.paterno}  ${usuario.materno}`
                            }
                        ]
                    ]
                },
                layout: {
                    defaultBorder: false,
                }
            },
            {
                text: 'Declaro bajo juramento que los datos arriba consignados son fidedignos y corresponden fielmente a la verdad, en consecuencia asumo responsabilidad de lo declarado.',
                style: 'paragraf',
                bold: true
            }
        ],
        styles: {
            header: {
                fontSize: 12,
                bold: true,
                alignment: 'center',
                margin: [0, 0, 0, 0]
            },
            headerdos: {
                fontSize: 12,
                alignment: 'center',
                margin: [0, 0, 0, 0]
            },
            subheader: {
                font: 'Roboto',
                fontSize: 9,
                bold: true,
                margin: [0, 10, 0, 0]
            },
            paragraf: {
                font: 'Roboto',
                fontSize: 9,
            },
            tableExample: {
                margin: [0, 0, 0, 0],
                font: 'Roboto',
                fontSize: 9
            },
        },
        defaultStyle: {
            alignment: 'justify',
        }

    }

    const [url, setUrl] = useState(null)

    const crearPdf = () => {
        guardar()
        pdfMake.vfs = {
            ...pdfFonts.pdfMake.vfs,
        };
        pdfMake.fonts = {
            // Default font should still be available
            Roboto: {
                normal: 'Roboto-Regular.ttf',
                bold: 'Roboto-Medium.ttf',
                italics: 'Roboto-Italic.ttf',
                bolditalics: 'Roboto-Italic.ttf'
            },
        }
        const generarPDF = pdfMake.createPdf(docDefinition);
        generarPDF.getBlob((blob) => {
            const url = URL.createObjectURL(blob);
            setUrl(url)
        })
        generarPDF.open()
    }
    return (
        <div className='container col-12 p-0'>
            <button className='col-12 cabecera_background m-2 text-white' style={{ width: '190px', borderRadius: '8px' }}
                onClick={crearPdf}
            >
                <p className='m-0'>{nombre}</p>
            </button>
        </div>
    );
}


export default FormatoDocPDF;