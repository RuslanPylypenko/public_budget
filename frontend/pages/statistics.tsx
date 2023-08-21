import React from "react";
import { Htag } from "../components";
import { withLayout } from '../layout/Layout';

function Statistics(): JSX.Element {
    return (
        <div className="container">
            <Htag tag='h1'>Statistics</Htag>
        </div>
    );
}

export default withLayout(Statistics);