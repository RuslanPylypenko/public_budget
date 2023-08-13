import React from "react";
import { Htag } from "../components";
import { withLayout } from '../layout/Layout';

function Statistics(): JSX.Element {
    return (
        <>
            <Htag tag='h1'>Statistics</Htag>
        </>
    );
}

export default withLayout(Statistics);