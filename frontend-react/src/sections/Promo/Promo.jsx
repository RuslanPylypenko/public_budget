import styles from './Promo.module.scss'

import { Container, Htag, Ptag } from "../../components";
import React, { useState, useEffect } from 'react';

export function Promo() {
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
        <section className={styles.promo} style={{ backgroundImage: `url("/background/header.svg")`  }}>
            <Container>
                <div className={styles.wrapper}>
                    <a href="/" className={styles.logo} style={{ backgroundImage: `url("/lviv.png")` }} />
                    <div className={styles.description}>
                        <Htag tag='h1'>{city && city.mainTitle}</Htag>
                        <Ptag size='p2'>{city && city.mainText}</Ptag>
                    </div>
                </div>
            </Container>
        </section>
    )
}