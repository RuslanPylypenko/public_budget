import Head from "next/head";
import React from "react";
import { AppProps } from 'next/dist/next-server/lib/router/router';
import '../styles/global.css';

export default function MyApp({ Component, pageProps }: AppProps): JSX.Element {
  return (
      <>
        <Head>
          <title>City Budget</title>
            <link rel="icon" href="/favicon.ico" />
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link rel="preconnect" href="https://fonts.gstatic.com" />
            <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,300;8..60,400;8..60,500;8..60,600;8..60,700;8..60,800&display=swap" rel="stylesheet" />
        </Head>
        <Component {...pageProps} />
      </>
  )
}
