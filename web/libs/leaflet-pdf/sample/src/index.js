import L from "Leaflet";
import { PDFJS } from "PDFJS-dist";
import PDFLayer from "../../src/PDFLayer";

PDFJS.workerSrc = "dist/worker.js";

const map = L.map("map", {
  minZoom: 4,
  maxZoom: 18
});

map.setView([48.709111, 1.375194], 15);

L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

new PDFLayer({
  pdf: "vernouillet.pdf",
  page: 1,
  minZoom: map.getMinZoom(),
  maxZoom: map.getMaxZoom(),
  bounds: new L.LatLngBounds([48.704718, 1.370309], [48.714007, 1.376781]),
  attribution:
    '<a href="http://cadastre.gouv.fr">cadastre</a>'
}).addTo(map);
