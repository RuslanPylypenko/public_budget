import React from "react";
import {Button, Htag, P} from "../components";

export default function Home(): JSX.Element {
  return (
      <>
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
      </>
  );
}
