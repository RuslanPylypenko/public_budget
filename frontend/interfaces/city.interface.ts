export interface City {
    name: string;
    techName: string;
    mainTitle: string;
    mainText: string;
    lat: number;
    lon: number;
}

export interface CityInterface {
    city: City[];
}