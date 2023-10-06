import styled from "styled-components";
import { format } from 'date-fns';

const FooterEl = styled.footer`
  padding: 20px;
  color: var(--white);
  background-color: var(--slateGray);
  @media (max-width: 1199px) {
    text-align: center;
  }
`;

const Wrapper = styled.div`
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 0 10px;
  @media (max-width: 1199px) {
    grid-template-columns: 1fr;
  }
`;

const Span = styled.span `
  white-space: nowrap;
`;

export const Footer = () => {
    return (
        <FooterEl>
            <Wrapper>
                <div>
                    Телефон для довідок: (099)1234567 <Span>© { format(new Date(), 'yyyy') }</Span>
                </div>
                <div>
                    При використанні матеріалів посилання на сайт обов’язкове. Всі права захищені.
                </div>
            </Wrapper>
        </FooterEl>
    );
};