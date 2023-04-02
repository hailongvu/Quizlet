import {Country, State, City} from 'country-state-city';

export const country_data = {
    getCountry,
    getState,
    getCity
}

function getCountry() {
    return Country.getAllCountries();
}

function getState(country_id) {
    return State.getStatesOfCountry(country_id);
}

function getCity(country_id, state_id) {
    return City.getCitiesOfState(country_id, state_id);
}
