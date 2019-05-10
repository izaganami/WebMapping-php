<?php include("config.php") ?>
<?php include(INCLUDE_PATH . "/logic/common_functions.php"); ?>
    <!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://openlayers.org/en/v3.20.1/css/ol.css" type="text/css">
        <meta charset="utf-8">
        <title>UserAccounts - Home</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
        <!-- Custome styles -->
        <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
        <script src="https://openlayers.org/en/v3.20.1/build/ol.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.js"></script>
        <script src="assets/js/Olscript.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.4.3/proj4.js"></script>

        <script src="https://epsg.io/3857.js"></script>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
<body>
<?php include(INCLUDE_PATH . "/layouts/navbar.php") ?>
<?php include(INCLUDE_PATH . "/layouts/messages.php") ?>
    <h1>Home page</h1>

    <div class="row-fluid">
        <table class="table table-borderless">
            <tr class="span12" style="position: center">
                <td id="map" class="map"  style="height: 600px;width:600px;max-width: 800px ; max-height: 600px;" ></td>
                <td>
                    <img style="max-height: 180px;width:150px" src="assets/images/leleg.png">


                    <img style="max-height: 160px" src="assets/images/download.jpg">

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked="checked" id="layer1">
                        <label class="form-check-label" for="layer1" >BaseMap</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked="checked" id="layer2">
                        <label class="form-check-label" for="layer2" >Provinces</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked="checked" id="layer3">
                        <label class="form-check-label" for="layer3" >Localites</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked="checked" id="layer4">
                        <label class="form-check-label" for="layer4" >Taux</label>
                    </div>

                    <form class="form" class="form-group">
                        <label>Page size </label>
                        <select id="format" class="form-control" style="max-width: 25%">
                            <option value="a0">A0 (slow)</option>
                            <option value="a1">A1</option>
                            <option value="a2">A2</option>
                            <option value="a3">A3</option>
                            <option value="a4" selected>A4</option>
                            <option value="a5">A5 (fast)</option>
                        </select>
                        <label>Resolution </label>
                        <select id="resolution" class="form-control" style="max-width: 25%">
                            <option value="72">72 dpi (fast)</option>
                            <option value="150">150 dpi</option>
                            <option value="300">300 dpi (slow)</option>
                        </select>
                    </form>
                    <br>
                    <button id="export-pdf" class="btn btn-primary">Export PDF</button>
                </td>
            </tr>
        </table>
    </div>
    <script>
        var controls = [
            new ol.control.Attribution(),
            new ol.control.MousePosition({
                undefinedHTML: 'outside',
                projection: 'EPSG:4326',
                coordinateFormat: function(coordinate) {
                    return ol.coordinate.format(coordinate, '{x}, {y}', 4);
                }
            }),
            new ol.control.OverviewMap({
                collapsed: false
            }),
            new ol.control.Rotate({
                autoHide: false
            }),
            new ol.control.ScaleLine(),
            new ol.control.Zoom(),
            new ol.control.ZoomSlider(),
            new ol.control.ZoomToExtent(),
            new ol.control.FullScreen()
        ];

        var image = new ol.style.Circle({
            radius: 3,
            fill: new ol.style.Fill({
                color: 'red'
            }),
            stroke: new ol.style.Stroke({color: 'black', width: 1})
        });
        var image2 = new ol.style.Circle({
            radius: 0,
            fill: new ol.style.Fill({
                color: 'rgba(195,231,58,1.00)'
            }),
            stroke: new ol.style.Stroke({color: 'black', width: 1})
        });
        var StyleMP=new ol.style.Style({
            geometry: 'MultiPolygon',
            stroke: new ol.style.Stroke({
                color: 'red',
                width: 1
            }),
            fill: new ol.style.Fill({
                color: 'rgba(255,9,88,0.1)'
            })
        });
        var styles2 = {
            'Point': new ol.style.Style({
                image: image2
            })
        };
        var styles = {
            'Point': new ol.style.Style({
                image: image
            }),
            'LineString': new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'green',
                    width: 1
                })
            }),
            'MultiLineString': new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'green',
                    width: 1
                })
            }),
            'MultiPoint': new ol.style.Style({
                image: image
            }),
            'MultiPolygon': new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'black',
                    width: 1
                }),
                fill: new ol.style.Fill({
                    color: 'rgba(255, 255, 0, 0.1)'
                })
            }),
            'Polygon': new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'blue',
                    lineDash: [4],
                    width: 3
                }),
                fill: new ol.style.Fill({
                    color: 'rgba(0, 0, 255, 0.1)'
                })
            }),
            'GeometryCollection': new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'magenta',
                    width: 2
                }),
                fill: new ol.style.Fill({
                    color: 'magenta'
                }),
                image: new ol.style.Circle({
                    radius: 10,
                    fill: null,
                    stroke: new ol.style.Stroke({
                        color: 'magenta'
                    })
                })
            }),
            'Circle': new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'black',
                    width: 1
                }),
                fill: new ol.style.Fill({
                    color: 'rgb(0,0,0)'
                })
            })
        };

        var styleFunction = function(feature) {
            if (feature.get('NOMBRE_DE_') >451.40&& feature.get('NOMBRE_DE_')<=594.20) {

                return new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'black',
                        width: 1
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(42,146,74,1.00)'
                    })
                });
            }
            if (feature.get('NOMBRE_DE_') >23.00 && feature.get('NOMBRE_DE_')<=165.80) {

                return new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'black',
                        width: 1
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(247,252,245,1.00)'
                    })
                });
            }
            if (feature.get('NOMBRE_DE_') >165.80 && feature.get('NOMBRE_DE_')<=308.60) {

                return new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'black',
                        width: 1
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(202, 234, 195, 1.00)'
                    })
                });
            }
            if (feature.get('NOMBRE_DE_') >308.60 && feature.get('NOMBRE_DE_')<=451.40) {

                return new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'black',
                        width: 1
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(123,200,124,1.00)'
                    })
                });
            }
            if (feature.get('NOMBRE_DE_') >594.20&& feature.get('NOMBRE_DE_')<=737.00) {
                return new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'black',
                        width: 1
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(0,68,27,1.00)'
                    })
                });
            }

            else {

                return styles[feature.getGeometry().getType()];
            }
        };

        var styleFunction2 = function(feature) {
            console.log('h');
            if (feature.get('TAUX_D_ACC') >0 && feature.get('TAUX_D_ACC')<20) {

                return new ol.style.Style({image: new ol.style.Circle({
                        radius: 1.5,
                        fill: new ol.style.Fill({
                            color: 'rgba(195,231,58,1.00)'
                        }),
                        stroke: new ol.style.Stroke({color: 'black', width: 1})
                    })});
            }
            if (feature.get('TAUX_D_ACC') >20 && feature.get('TAUX_D_ACC')<40) {

                return new ol.style.Style({image: new ol.style.Circle({
                        radius: 3,
                        fill: new ol.style.Fill({
                            color: 'rgba(195,231,58,1.00)'
                        }),
                        stroke: new ol.style.Stroke({color: 'black', width: 1})
                    })});
            }if (feature.get('TAUX_D_ACC') >40 && feature.get('TAUX_D_ACC')<60) {

                return new ol.style.Style({image: new ol.style.Circle({
                        radius: 4.5,
                        fill: new ol.style.Fill({
                            color: 'rgba(195,231,58,1.00)'
                        }),
                        stroke: new ol.style.Stroke({color: 'black', width: 1})
                    })});
            }if (feature.get('TAUX_D_ACC') >60 && feature.get('TAUX_D_ACC')<80) {

                return new ol.style.Style({image: new ol.style.Circle({
                        radius: 6,
                        fill: new ol.style.Fill({
                            color: 'rgba(195,231,58,1.00)'
                        }),
                        stroke: new ol.style.Stroke({color: 'black', width: 1})
                    })});
            }if (feature.get('TAUX_D_ACC') >80 && feature.get('TAUX_D_ACC')<100) {

                return new ol.style.Style({image: new ol.style.Circle({
                        radius: 7.5,
                        fill: new ol.style.Fill({
                            color: 'rgba(195,231,58,1.00)'
                        }),
                        stroke: new ol.style.Stroke({color: 'black', width: 1})
                    })});
            }

            else {

                return styles2[feature.getGeometry().getType()];
            }
        };

        var dataProjection = ol.proj.get('EPSG:3857');


        var raster = new ol.layer.Tile({
            title: 'Open Street Map',
            source: new ol.source.OSM(),
            type: 'base',

        });


        var vectorL=new ol.layer.Vector({
            source:new ol.source.Vector({
                format:new ol.format.GeoJSON({featureProjection:'EPSG:3857'}),
                url:"assets/Geojson/Pov/pov.geojson",

            }),
            style:styleFunction
        });


        var vectorL2=new ol.layer.Vector({
            source:new ol.source.Vector({
                format:new ol.format.GeoJSON({featureProjection:'EPSG:3857'}),
                url:"assets/Geojson/Pov/loc.geojson",

            }),
            style:styleFunction
        });

        var vectorL3=new ol.layer.Vector({
            source:new ol.source.Vector({
                format:new ol.format.GeoJSON({featureProjection:'EPSG:3857'}),
                url:"assets/Geojson/Pov/taux.geojson",

            }),
            style:styleFunction2
        });

        /*var provinces = new ol.layer.Tile({

            source: new ol.source.TileWMS({

                url: 'http://localhost:8080/geoserver/cite/wms',

                params: {

                    'LAYERS': 'provinces_maroc',

                    'TRANSPARENT': 'true',

                    'WIDTH': 640,

                    'HEIGHT': 480
                },
                projection: 'EPSG:3857'
            })
        });*/

        var olCoordinates = ol.proj.fromLonLat([-8, 32]);
        var map = new ol.Map({
            target: 'map',
            layers:[raster,vectorL,vectorL2,vectorL3],


            controls: controls,
            view: new ol.View({
                center:olCoordinates,
                zoom: 5
            })
        });

        var dims = {
            a0: [1189, 841],
            a1: [841, 594],
            a2: [594, 420],
            a3: [420, 297],
            a4: [297, 210],
            a5: [210, 148]
        };

        var loading = 0;
        var loaded = 0;

        var exportButton = document.getElementById('export-pdf');

        exportButton.addEventListener('click', function() {

            exportButton.disabled = true;
            document.body.style.cursor = 'progress';

            var format = document.getElementById('format').value;
            var resolution = document.getElementById('resolution').value;
            var dim = dims[format];
            var width = Math.round(dim[0] * resolution / 25.4);
            var height = Math.round(dim[1] * resolution / 25.4);
            var size = /** @type {ol.Size} */ (map.getSize());
            var extent = map.getView().calculateExtent(size);

            var source = raster.getSource();

            var tileLoadStart = function() {
                ++loading;
            };

            var tileLoadEnd = function() {
                ++loaded;
                if (loading === loaded) {
                    var canvas = this;
                    window.setTimeout(function() {
                        loading = 0;
                        loaded = 0;
                        var data = canvas.toDataURL('image/png');
                        var pdf = new jsPDF('landscape', undefined, format);
                        pdf.addImage(data, 'JPEG', 0, 0, dim[0], dim[1]);
                        pdf.save('map.pdf');
                        source.un('tileloadstart', tileLoadStart);
                        source.un('tileloadend', tileLoadEnd, canvas);
                        source.un('tileloaderror', tileLoadEnd, canvas);
                        map.setSize(size);
                        map.getView().fit(extent, size);
                        map.renderSync();
                        exportButton.disabled = false;
                        document.body.style.cursor = 'auto';
                    }, 100);
                }
            };

            map.once('postcompose', function(event) {
                source.on('tileloadstart', tileLoadStart);
                source.on('tileloadend', tileLoadEnd, event.context.canvas);
                source.on('tileloaderror', tileLoadEnd, event.context.canvas);
            });

            map.setSize([width, height]);
            map.getView().fit(extent, /** @type {ol.Size} */ (map.getSize()));
            map.renderSync();

        }, false);

        $('input[type=checkbox]').on('change', function () {
            var layer = {
                layer1: raster,
                layer2: vectorL,
                layer3: vectorL2,
                layer4:vectorL3,
            }[$(this).attr('id')];
            layer.setVisible(!layer.getVisible());
        });
        map.on('click', function(evt){
            var feature = map.forEachFeatureAtPixel(
                evt.pixel, function(ft, l) { return ft; }
            );

            if (feature) {
                //you can see all properties with getProperties()
                console.info(feature.getProperties());

                //and you can get directly a property
                console.info(feature.get('any-property'));
            }
        });

    </script>
<?php include(INCLUDE_PATH . "/layouts/footer.php") ?>