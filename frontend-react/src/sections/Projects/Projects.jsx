import React from "react";
import {Statistics} from "..";

export function Projects({ stage, statistic }) {

    return (
        <div>
            <Statistics stage={stage} statistic={statistic} />
        </div>
    )
}
