
import Form from 'react-bootstrap/Form';

function Total({label, suma}) {
  return (
    <div className='container col p-0'>
      <div className='row categoria_background align-items-center p-1'>
       <div className="col-8">
            <Form.Label style={{ height: '25px' }}>{label}</Form.Label>
        </div>
        <div className="col-4" >
            <Form.Control style={{ height: '25px', textAlign:'end' }} type="text" placeholder="Bs." 
              value={suma} disabled
            />
        </div>
      </div>
    </div>
  );
}
export default Total;