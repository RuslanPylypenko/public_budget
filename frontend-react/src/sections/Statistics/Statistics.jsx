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
  margin-bottom: 20px;
  img {
    height: 65px;
    margin-right: 10px;
  }
  strong {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 5px;
    padding: 2px 8px;
    border-radius: 2px;
    background: var(--white);
    color: var(--darkGray);
  }
`;

function CountersE1({ stage, statistic }) {
    switch (stage) {
        case 'submission':
            return (
                <>
                    <Htag tag='h2'>
                        Подано проектів <strong>{statistic.projects_count}</strong> на суму <strong>{new Intl.NumberFormat().format(statistic.budget_sum)}</strong> грн
                    </Htag>
                </>
            );
        case 'review':
            return (
                <>
                    <Htag tag='h2'>
                        На розгляді <strong>{statistic.projects_count}</strong> На суму <strong>{new Intl.NumberFormat().format(statistic.budget_sum)}</strong> грн
                    </Htag>
                </>
            );
        case 'voting':
            return (
                <>
                    <Htag tag='h2'>
                        Осіб всього проголосувало <strong>{statistic.voters_total}</strong>
                    </Htag>
                </>
            );
        case 'decision':
            return (
                <>
                    <Htag tag='h2'>
                        Переможці <strong>{statistic.voters_total}</strong>
                    </Htag>
                </>
            );
        case 'implementation':
            return (
                <>
                    <img src="https://if.pb.org.ua/assets/app/img/medal-thin.svg" alt="medal" />
                    <Htag tag='h2'>В процесі реалізації</Htag>
                    <strong>{statistic.projects_count}</strong>
                </>
            )
    }
}

export function Statistics({ stage, statistic }) {

    return (
        <StatisticsE1 style={{ backgroundImage: `url("/background/statistics.svg")` }}>
            <Counters>
                {statistic && <CountersE1 stage={stage} statistic={statistic} />}
            </Counters>
            <Button appearance='ghost' style={{'mix-blend-mode': 'screen'}}>Детальна статистика</Button>
        </StatisticsE1>
    )
}
