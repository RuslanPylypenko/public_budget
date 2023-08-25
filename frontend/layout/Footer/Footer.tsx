import { FooterProps } from './Footer.props';
import styles from './Footer.module.css';
import cn from "classnames";
import { format } from 'date-fns';

export const Footer = ({className, ...props}: FooterProps): JSX.Element => {
    return (
        <footer className={cn(className, styles.footer)} {...props}>
            <div>
                Телефон для довідок: (099)1234567 <span>© { format(new Date(), 'yyyy') }</span>
            </div>
            <div>
                При використанні матеріалів посилання на сайт обов’язкове. Всі права захищені.
            </div>
        </footer>
    );
};