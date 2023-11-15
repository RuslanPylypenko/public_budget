import styles from './Ptag.module.scss';
import cn from "classnames";

export const Ptag = ({ size = 'p5', children , className, ...props}) => {
    return (
        <p
            className={ cn(className, {
                [styles.p1]: size === 'p1',
                [styles.p2]: size === 'p2',
                [styles.p3]: size === 'p3',
                [styles.p4]: size === 'p4',
                [styles.p5]: size === 'p5',
            }) }
            {...props}
        >
            {children}
        </p>
    );
};