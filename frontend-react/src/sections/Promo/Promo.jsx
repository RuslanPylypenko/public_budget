import styled from "styled-components";
import { Container, Htag, Ptag } from "../../components";
import React, { useState, useEffect } from 'react';

const PromoE1 = styled.section `
  background-color: var(--accent);
  background-position: bottom;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
  min-height: 485px;
  &:before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: #fff;
    opacity: 0.5;
  }
`;

const Wrapper = styled.div`
  display: flex;
  position: relative;
  padding: 120px 0 180px;
`;

const Logo = styled.a.attrs({
    href: '/',
})`
  background-size: contain;
  background-position: top right;
  background-repeat: no-repeat;
  min-width: 10%;
`;

const Description = styled.div`
  padding: 0 40px;
  color: var(--darkGray);
`;

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
        <PromoE1 style={{ backgroundImage: `url("/background/header.svg")` }}>
            <Container>
                <Wrapper>
                    <Logo style={{ backgroundImage: `url("/lviv.png")` }} />
                    <Description>
                        <Htag tag='h1'>{city && city.mainTitle}</Htag>
                        <Ptag size='p2'>{city && city.mainText}</Ptag>
                    </Description>
                </Wrapper>
            </Container>
        </PromoE1>
    )
}