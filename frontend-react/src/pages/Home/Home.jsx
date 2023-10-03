import { Button, Container, Htag, Label, Ptag } from "../../components";
import React, { useState, useEffect } from 'react';

export function Home() {
    let [city, setCity] = useState(null);

    useEffect(() => {
        fetch('http://localhost:8081/api/city/', {
            method: 'GET',
        })
            .then( response => response.json() )
            .then( data => setCity(data.city) )
            .catch( err => console.log('Error', err) );
    }, []);

    return (
        <>
            <section style={{ padding: `80px 0` }}>
                <Htag tag='h1'>{city && city.mainTitle}</Htag>
            </section>

            <Container>
                <section style={{ padding: `80px 0` }}>
                    <Htag tag='h1'>Heading level 1</Htag>
                    <Htag tag='h2'>Heading level 2</Htag>
                    <Htag tag='h3'>Heading level 3</Htag>

                    <Button appearance='primary'>Button Primary</Button>
                    <Button appearance='accent' arrow>Button Accent</Button>
                    <Button appearance='ghost'>Button Ghost</Button>

                    <Ptag size='p1'>Paragraph size 1</Ptag>
                    <Ptag size='p2'>Paragraph size 2</Ptag>
                    <Ptag size='p3'>Paragraph size 3</Ptag>
                    <Ptag size='p4'>Paragraph size 4</Ptag>
                    <Ptag size='p5'>Paragraph size 5</Ptag>

                    <Label>Номер: 175</Label>
                    <Label status='participantStatus'>Відхилений</Label>
                    <Label status='rejectedStatus'>Брав участь</Label>
                    <Label status='finishedStatus'>Реалізований</Label>
                </section>
            </Container>
        </>
    );
}


