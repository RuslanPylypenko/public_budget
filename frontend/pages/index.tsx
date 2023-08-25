import React, { useState } from "react";
import { Button, Htag, Label, P } from "../components";
import { withLayout } from '../layout/Layout';
import {GetStaticProps} from "next";
import axios from 'axios';
import {CityInterface} from "../interfaces/city.interface";
import Link from "next/link";

function Home({ cityInfo }: HomeProps): JSX.Element {
   const cityData: any = cityInfo;

   return (
      <>
          <section className='promo' style={{ backgroundImage: `url("/header.svg")`  }}>
              <div className="container">
                  <div className="promo__container">
                      <Link href="/" >
                          <a className="promo__logo" style={{ backgroundImage: `url("/lviv.svg.png")` }}></a>
                      </Link>
                      <div className="promo__descr">
                          <Htag tag='h1'>{cityData.city.mainTitle}</Htag>
                          <P size='p2'>{cityData.city.mainText}</P>
                      </div>
                  </div>
              </div>
          </section>

          <div className="container">
              <section style={{ padding: `120px 0` }}>
                  <Htag tag='h1'>Heading level 1</Htag>
                  <Htag tag='h2'>Heading level 2</Htag>
                  <Htag tag='h3'>Heading level 3</Htag>

                  <Button appearance='primary'>Button Primary</Button>
                  <Button appearance='accent' arrow>Button Accent</Button>
                  <Button appearance='ghost'>Button Ghost</Button>

                  <P size='p1'>Paragraph size 1</P>
                  <P size='p2'>Paragraph size 2</P>
                  <P size='p3'>Paragraph size 3</P>
                  <P size='p4'>Paragraph size 4</P>
                  <P size='p5'>Paragraph size 5</P>

                  <Label>Номер: 175</Label>
                  <Label status='participantStatus'>Відхилений</Label>
                  <Label status='rejectedStatus'>Брав участь</Label>
                  <Label status='finishedStatus'>Реалізований</Label>
              </section>
          </div>
      </>
  );
}

export default withLayout(Home);

export const getStaticProps: GetStaticProps<HomeProps> = async () => {
    const { data: cityInfo } = await axios.get<CityInterface[]>(process.env.NEXT_PUBLIC_DOMAIN + '/api/city/');

    return {
        props: {
            cityInfo
        }
    }
};

interface HomeProps extends Record<string, unknown>{
    cityInfo: CityInterface[];
}