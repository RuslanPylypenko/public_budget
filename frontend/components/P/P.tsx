import { PProps } from './P.props';
import styles from './P.module.css';
import cn from "classnames";

export const P = ({ size = 'p5', children , className, ...props}: PProps): JSX.Element => {
    return (
        <p
            className={ cn(styles.p, className, {
                [styles.p1]: size == 'p1',
                [styles.p2]: size == 'p2',
                [styles.p3]: size == 'p3',
                [styles.p4]: size == 'p4',
                [styles.p5]: size == 'p5',
            }) }
            {...props}
        >
            {children}
        </p>
    );
};