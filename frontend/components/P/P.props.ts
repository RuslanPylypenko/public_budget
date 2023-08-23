import {DetailedHTMLProps, HTMLAttributes, ReactNode} from 'react';

export interface PProps extends DetailedHTMLProps<HTMLAttributes<HTMLParagraphElement>, HTMLParagraphElement> {
    size?: 'p1' | 'p2' | 'p3' | 'p4' | 'p5';
    children: ReactNode;
}