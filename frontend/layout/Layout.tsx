import {FunctionComponent} from "react";
import { Header } from './Header/Header'
import { Footer } from './Footer/Footer'
import { LayoutProps } from './Layout.props';
import styles from './Layout.module.scss';

const Layout = ({ children}: LayoutProps): JSX.Element => {
    return (
        <div className={styles.wrapper}>
            <Header className={styles.header} />
            <div className={styles.body}>
                {children}
            </div>
            <Footer className={styles.footer} />
        </div>
    );
};

export const withLayout = <T extends Record<string, unknown>>(Component: FunctionComponent<T>) => {
    return function withLayoutComponent(props: T) {
        return (
            <Layout>
                <Component {...props} />
            </Layout>
        );
    };
};