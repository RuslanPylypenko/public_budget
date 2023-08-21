import React, { useState } from "react";
import { Button, Htag, Label, P } from "../components";
import { withLayout } from '../layout/Layout';

function Home(): JSX.Element {
  return (
      <div className="container">
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
      </div>
  );
}

export default withLayout(Home);