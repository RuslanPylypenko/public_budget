import Head from "next/head";
import React from "react";
import { AppProps } from 'next/dist/next-server/lib/router/router';
import '../styles/global.css';

export default function MyApp({ Component, pageProps }: AppProps): JSX.Element {
  return (
      <>
        <Head>
          <title>Main page</title>
            <link rel="icon" href="/favicon.ico" />
        </Head>
        <Component {...pageProps} />
      </>
  )
}
