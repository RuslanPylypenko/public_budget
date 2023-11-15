import styled from 'styled-components';
import { useState, useEffect } from 'react';
import { IoMoon, IoSunny } from 'react-icons/io5';
import { Container } from "../../components";

const HeaderEl = styled.header`
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 99;
  box-shadow: var(--shadow);
  background-color: #363636;
`;

const Wrapper = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 45px;
`;

const Logo = styled.a.attrs({
    href: '/',
})`
  color: var(--lightGray);
  font-size: var(--fs-sm);
  font-weight: var(--fw-bold);
  text-decoration: none;
`;

const ModeSwitcher = styled.div`
  display: flex;
  align-items: center;
  color: var(--lightGray);
  font-size: var(--fs-sm);
  font-weight: var(--fw-bold);
  cursor: pointer;
  text-transform: capitalize;
  background: #4a4a4a;
  padding: 0 25px;
  height: 100%;
`;

export const Header = () => {
    const [theme, setTheme] = useState('light');

    const toggleTheme = () => setTheme(theme === 'light' ? 'dark' : 'light');

    useEffect(() => {
        document.body.setAttribute('data-theme', theme);
    }, [theme]);
    
    return (
        <HeaderEl>
            <Container>
                <Wrapper>
                    <Logo>Logo</Logo>
                    <ModeSwitcher onClick={toggleTheme}>
                        {theme === 'light' ? (
                            <IoSunny size="14px" />
                        ) : (
                            <IoMoon size="14px" />
                        )}{' '}
                        <span style={{ marginLeft: '0.75rem' }}>{theme} Theme</span>
                    </ModeSwitcher>
                </Wrapper>
            </Container>
        </HeaderEl>
    );
};