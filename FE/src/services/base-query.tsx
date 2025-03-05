// Or from '@reduxjs/toolkit/query/react'
import { createApi, fetchBaseQuery } from  "@reduxjs/toolkit/query/react";


const baseQuery = fetchBaseQuery({
  baseUrl: process.env.NEXT_PUBLIC_API_BASE_URL,
  prepareHeaders: (headers) => {
    headers.set("Accept", "application/json");
    return headers;
  },
})
export const apiSlice = createApi({
  // Set the baseUrl for every endpoint below
  baseQuery: baseQuery,
  endpoints: () => ({}),
});
