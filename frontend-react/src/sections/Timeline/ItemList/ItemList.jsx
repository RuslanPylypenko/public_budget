import React from 'react';
import styled from "styled-components";
import styles from './ItemList.module.scss';
import cn from 'classnames';

const ItemListE1 = styled.li `
  position: relative;
  flex: 1 1 0%;
  flex-basis: 20%;
  text-align: center;
  color: #a6d0eb;
  list-style: none;
  
  &.past {
    color: #cfd8dc;
  }
  .pastRibbon {
    background: #cfd8dc;
    &:before {
      border-color: #cfd8dc #cfd8dc #cfd8dc transparent;
    }
    &:after {
      border-left-color: #cfd8dc;
    }
  }
  .pastIcon {
    border-color: #cfd8dc;
  }
  
  &.active {
    color: #459ddd;
    flex-basis: 35%;
  }
  .activeRibbon {
    background: #459ddd;
    &:before {
      border-color: #459ddd #459ddd #459ddd transparent;
    }
    &:after {
      border-left-color: #459ddd;
    }
  }
  .activeIcon {
    border-color: #459ddd;
  }
`;

export function ItemList({ status }) {
    return (
        <ItemListE1 className={ status }>
            <div className={styles.itemWrap}>
                <div className={styles.date}>
                    <time dateTime="2023-03-30" className={styles.time}>до 30.03.2023</time>
                </div>
                <div className={styles.iconWrap}>
                    <div className={ cn(styles.ribbon, status + "Ribbon") } />
                    <div className={ cn(styles.icon, status + "Icon") }>
                        <img src="https://if.pb.org.ua/assets/app/img/stages/stage-submission_disabled.svg"
                             alt="Подання" />
                    </div>
                </div>
                <div className={styles.name}>Подання</div>
            </div>
        </ItemListE1>
    )
}