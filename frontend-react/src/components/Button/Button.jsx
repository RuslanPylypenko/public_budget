import styles from './Button.module.scss';
import { IoEnter } from 'react-icons/io5';
import cn from 'classnames';

export const Button = ({ appearance, arrow, children, className, ...props }) => {
    return (
        <button
            className={ cn(styles.button, className, {
                [styles.primary]: appearance === 'primary',
                [styles.accent]: appearance === 'accent',
                [styles.ghost]: appearance === 'ghost',
            }) }
            {...props}
        >
            {arrow && <span className={styles.arrow}><IoEnter /></span>}{children}
        </button>
    );
};
