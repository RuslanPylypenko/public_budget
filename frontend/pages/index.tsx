import React from "react";
import {Button, Htag} from "../components";

export default function Home(): JSX.Element {
  return (
      <>
          <Htag tag='h1'>Heading level 1</Htag>
          <Htag tag='h2'>Heading level 2</Htag>
          <Htag tag='h3'>Heading level 3</Htag>

          <Button appearance='primary'>Button Primary</Button>
          <Button appearance='accent' arrow>Button Accent</Button>
          <Button appearance='ghost'>Button Ghost</Button>
      </>
  );
}
