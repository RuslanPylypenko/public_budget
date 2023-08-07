import { ButtonProps } from "./Button.props";
import styles from './Button.module.css';
import ArrowIcon from './arrow.svg';
import cn from 'classnames';

export const Button = ({ appearance, arrow, children, className, ...props }: ButtonProps): JSX.Element => {
    return (
        <button
            className={ cn(styles.button, className, {
                [styles.primary]: appearance == 'primary',
                [styles.accent]: appearance == 'accent',
                [styles.ghost]: appearance == 'ghost',
            }) }
            {...props}
        >
            {arrow &&
            <span className={styles.arrow}>
                <ArrowIcon />
            </span>}
            {children}

        </button>
    );
};
