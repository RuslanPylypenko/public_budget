import styled from "styled-components";
import React, { useState, useEffect } from 'react';
import {MapContainer, Marker, Popup, TileLayer, useMapEvents} from "react-leaflet";
import { IoLocation } from 'react-icons/io5';
import './Map.scss';
import L, {LatLng} from 'leaflet';

L.Icon.Default.imagePath = "https://unpkg.com/leaflet@1.5.0/dist/images/";

const Wrapper = styled.section`
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 485px;
  max-width: 100%;
  overflow: hidden;
`;

export function Map({ lat, lon }) {
    return (
        <Wrapper>
            <MapContainer center={[lat, lon]} zoom={13} scrollWheelZoom={false} >
                <TileLayer
                    attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                />
                <Marker position={[lat, lon]}>
                    <IoLocation size="14px" />
                </Marker>
            </MapContainer>
        </Wrapper>
    )
}