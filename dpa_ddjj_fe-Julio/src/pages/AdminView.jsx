import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';

const AdminView = () => {
  return (
    <Container>
      <Row>
        <Col md={{ span: 6, offset: 3 }}>
          <div className="form-inline">
            <label for="cedula" style={{ fontSize: '16px', fontFamily: 'Open Sans', color: '#e30613' }}>
              Cédula de identidad:
            </label>
            <input
              type="text"
              id="cedula"
              className="form-control text-center"
              style={{ color: '#003770', width: '150px', fontSize: '16px' }}
            />
          </div>
        </Col>
      </Row>
    </Container>
  );
};

export default AdminView;