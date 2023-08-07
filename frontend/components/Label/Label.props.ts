import {DetailedHTMLProps, HTMLAttributes, ReactNode} from "react";

export interface LabelProps extends DetailedHTMLProps<HTMLAttributes<HTMLSpanElement>, HTMLSpanElement>{
    children: ReactNode;
    status?: 'participantStatus' | 'rejectedStatus' | 'finishedStatus';
    href?: string;
}