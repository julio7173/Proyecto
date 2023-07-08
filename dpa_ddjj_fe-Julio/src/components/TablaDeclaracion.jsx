import { Table } from "react-bootstrap"
import { PencilSquare, PrinterFill, TrashFill } from "react-bootstrap-icons"
import DeclaracionJuradaServices from "../services/declaracionJuradaServices"
import { useNavigate } from "react-router-dom";
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
import { formatoPDF } from "../reports/formatoPDF";

function TablaDeclaracion({listaDeclaraciones}) {
    const navigate = useNavigate()

    const generarPDF = async(idDeclaracion) => {
        const response = await DeclaracionJuradaServices.getDeclaracion(idDeclaracion)
        if(response.status === 200 && response.data.success === true){
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
            const docDefinition = formatoPDF(
                response.data.fecha,
                response.data.usuario,
                response.data.categoria,
                response.data.categorias,
                response.data.instituciones)
            const generarPDF = pdfMake.createPdf(docDefinition);
            /*generarPDF.getBlob((blob) => {
                const url = URL.createObjectURL(blob);
            })*/
            generarPDF.open()
        }else{
            console.log('error', response)
        }
    }

    
    return (
        <Table striped bordered hover>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {
                    listaDeclaraciones.map(declaracion => (
                        <tr>
                            <td>{declaracion.fecha}</td>
                            <td style={{textAlign:"center"}}>
                                { declaracion.estado === 'PEN' ?
                                    <PencilSquare color="#003770" onClick={() => navigate(`/formulario/${declaracion.id_declaracion_jurada}`)}></PencilSquare>:null
                                }
                                <PrinterFill color="#003770" onClick={() => generarPDF(declaracion.id_declaracion_jurada)}></PrinterFill>
                                { declaracion.estado === 'PEN' ? 
                                    <TrashFill color="#003770"></TrashFill> : null
                                }
                            </td>
                        </tr>
                    ))
                }
            </tbody>
        </Table>
    )
}

export default TablaDeclaracion