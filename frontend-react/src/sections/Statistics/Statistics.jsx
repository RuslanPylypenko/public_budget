import styled from 'styled-components';
import React from "react";
import {Button, Htag} from "../../components";

const StatisticsE1 = styled.section `
  background-color: var(--accent);
  background-position: bottom;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 135px;
`;

const Counters = styled.div `
  display: flex;
  align-items: center;
  color: var(--white);
  img {
    height: 65px;
    margin-right: 10px;
  }
  strong {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin-left: 10px;
    padding: 8px;
    border-radius: 2px;
    background: var(--white);
    color: var(--darkGray);
  }
`;

export function Statistics({ counter }) {
    return (
        <StatisticsE1 style={{ backgroundImage: `url("/background/statistics.svg")` }}>
            <Counters>
                <img src="https://if.pb.org.ua/assets/app/img/medal-thin.svg" alt="medal" />
                <Htag tag='h2'>В процесі реалізації</Htag>
                <strong>{counter}</strong>
            </Counters>
            <Button appearance='ghost' style={{'mix-blend-mode': 'screen'}}>Детальна статистика</Button>
        </StatisticsE1>
    )
}
