import { Image } from "react-bootstrap";

function Home(){
    return(
        <div  className="row vh-100 justify-content-center align-items-center">
            <Image src="https://upload.wikimedia.org/wikipedia/commons/4/43/Marca_Vertical_Universidad_Mayor_de_San_Sim%C3%B3n_Cochabamba_Bolivia.png" fluid
            style={{ width: "500px", height: "500px"}} />
        </div>
    )
}

export default Home;