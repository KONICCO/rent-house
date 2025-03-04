// Or from '@reduxjs/toolkit/query/react'
import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query";

export const ApiSlice = createApi({
  // Set the baseUrl for every endpoint below
  baseQuery: fetchBaseQuery({
    baseUrl: process.env.NEXT_PUBLIC_API_BASE_URL,
    prepareHeaders: (headers) => {
      headers.set("Accept", "application/json");
      return headers;
    },
  }),
  endpoints: (build) => ({
    getPokemonByName: build.query({
      // Will make a request like https://pokeapi.co/api/v2/pokemon/bulbasaur
      query: (name: string) => `pokemon/${name}`,
    }),
    updatePokemon: build.mutation({
      query: ({ name, patch }) => ({
        url: `pokemon/${name}`,
        // When performing a mutation, you typically use a method of
        // PATCH/PUT/POST/DELETE for REST endpoints
        method: "PATCH",
        // fetchBaseQuery automatically adds `content-type: application/json` to
        // the Headers and calls `JSON.stringify(patch)`
        body: patch,
      }),
    }),
  }),
});
