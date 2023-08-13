import { FooterProps } from './Footer.props';
import styles from './Footer.module.css';
import cn from "classnames";
import { format } from 'date-fns';

export const Footer = ({className, ...props}: FooterProps): JSX.Element => {
    return (
        <footer className={cn(className, styles.footer)} {...props}>
            <div>
                Телефон для довідок: (099)1234567 © { format(new Date(), 'yyyy') }
            </div>
            <div>
                При використанні матеріалів посилання на сайт обов’язкове. Всі права захищені.
            </div>
        </footer>
    );
};