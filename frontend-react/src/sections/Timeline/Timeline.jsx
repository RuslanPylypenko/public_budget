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

export function Timeline({ stages }) {
    return (
        <TimelineE1>
            <Container>
                <UL>
                    { stages && stages.map((stage, i) => <ItemList key={i} stage={stage} />) }
                </UL>
            </Container>
        </TimelineE1>
    )
}