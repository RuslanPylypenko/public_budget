import { LabelProps } from './Label.props';
import styles from './Label.module.css';
import cn from "classnames";

export const Label = ({ children, status, href, className, ...props}: LabelProps): JSX.Element => {
    return (
        <span
            className={ cn(styles.label, className, {
                [styles.participantStatus]: status == 'participantStatus',
                [styles.rejectedStatus]: status == 'rejectedStatus',
                [styles.finishedStatus]: status == 'finishedStatus',
            }) }
            {...props}
        >
            {children}
        </span>
    );
};