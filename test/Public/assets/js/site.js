document.querySelectorAll('.reveal').forEach((el, i) => {
    el.style.animationDelay = `${i * 90}ms`;
});

const deptMapEl = document.getElementById('dept-map');

if (deptMapEl && window.L) {
    const targetDept = (deptMapEl.dataset.deptCode || '').toUpperCase();

    const map = L.map('dept-map', {
        zoomControl: false,
        attributionControl: false,
        dragging: false,
        doubleClickZoom: false,
        scrollWheelZoom: false,
        boxZoom: false,
        keyboard: false,
        tap: false,
        touchZoom: false,
    });

    const baseStyle = {
        color: '#8d8d8d',
        weight: 1,
        fillColor: '#f8f8f8',
        fillOpacity: 1,
    };

    const highlightStyle = {
        color: '#3f4d5a',
        weight: 1.5,
        fillColor: '#e8d99d',
        fillOpacity: 1,
    };

    fetch('assets/data/departements.geojson?v=2')
        .then((r) => {
            if (!r.ok) {
                throw new Error(`GeoJSON HTTP ${r.status}`);
            }
            return r.json();
        })
        .then((geojson) => {
            let highlightedBounds = null;

            const layer = L.geoJSON(geojson, {
                style: (feature) => {
                    const p = feature?.properties || {};
                    const code = String(p.code || p.code_dept || p.codeDepartement || '').toUpperCase();
                    return targetDept && code === targetDept ? highlightStyle : baseStyle;
                },
                onEachFeature: (feature, lyr) => {
                    const p = feature?.properties || {};
                    const code = String(p.code || p.code_dept || p.codeDepartement || '').toUpperCase();
                    if (targetDept && code === targetDept) {
                        highlightedBounds = lyr.getBounds();
                    }
                },
            }).addTo(map);

            if (highlightedBounds) {
                map.fitBounds(highlightedBounds.pad(1.6));
            } else {
                map.fitBounds(layer.getBounds());
            }
        })
        .catch(() => {
            deptMapEl.classList.add('contact-map--fallback');
        });
}
