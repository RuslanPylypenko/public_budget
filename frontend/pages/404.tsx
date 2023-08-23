import {Button, Htag} from "../components";
import React from "react";
import Link from "next/link";

export default function Error() {
    return (
        <>
            <Htag tag='h1'>404</Htag>
            <Button appearance='primary'>
                <Link href="/">
                    На головну
                </Link>

            </Button>
        </>
    )
}