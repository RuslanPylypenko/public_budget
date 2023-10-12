import React from 'react';
import styled from "styled-components";
import { Container } from "../../components";
import { ItemList } from "./ItemList/ItemList";

const TimelineE1 = styled.section `
    position: relative;
    padding: 20px 0;
    min-height: 220px;
`;

const UL = styled.ul `
  display: flex;
`;

export function Timeline() {
    return (
        <TimelineE1>
            <Container>
                <UL>
                    <ItemList status="past" />

                    <ItemList status="past" />
                    <ItemList status="past" />
                    <ItemList status="active" />
                    <ItemList />
                </UL>
            </Container>
        </TimelineE1>
    )
}