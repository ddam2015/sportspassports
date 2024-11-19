(function($) {
// get reference to make the form init request
var g365_script_element, g365_script_anchor, g365_script_url, g365_script_domain, g365_script_ajax, g365_script_ajax_request;
//function that sets the form init variables

//get the form insert element
g365_script_element = document.getElementById('g365_form_script');
//if there is a form element support it
if( typeof g365_script_element == 'undefined' || g365_script_element == null ) {
  //set global flag to false
  g365_script_anchor = false;
} else {
  //set global flag to true
  g365_script_url = (g365_script_element.hasAttribute('src')) ? g365_script_element.src : g365_script_element.getAttribute('data-g365_url');
  if( g365_script_url === '' || g365_script_url === null ) {
    g365_script_anchor = false;
  } else {
    g365_script_domain = g365_script_url.slice(0, (g365_script_url.indexOf('.com/') + 5));
    g365_script_ajax = g365_script_domain + 'data-process/';
    g365_script_ajax_request = g365_script_domain + 'data-request/';
    g365_script_anchor = true;
  } 
}
//g365 session array
window.g365_sess_data = [];
//if the init object isn't declared, add it
if( typeof window.g365_func_wrapper !== 'object' ) window.g365_func_wrapper={sess:[],found:[],end:[]};

(function() {

    var debug = false;

    var root = this;

    var EXIF = function(obj) {
        if (obj instanceof EXIF) return obj;
        if (!(this instanceof EXIF)) return new EXIF(obj);
        this.EXIFwrapped = obj;
    };

    if (typeof exports !== 'undefined') {
        if (typeof module !== 'undefined' && module.exports) {
            exports = module.exports = EXIF;
        }
        exports.EXIF = EXIF;
    } else {
        root.EXIF = EXIF;
    }

    var ExifTags = EXIF.Tags = {

        // version tags
        0x9000 : "ExifVersion",             // EXIF version
        0xA000 : "FlashpixVersion",         // Flashpix format version

        // colorspace tags
        0xA001 : "ColorSpace",              // Color space information tag

        // image configuration
        0xA002 : "PixelXDimension",         // Valid width of meaningful image
        0xA003 : "PixelYDimension",         // Valid height of meaningful image
        0x9101 : "ComponentsConfiguration", // Information about channels
        0x9102 : "CompressedBitsPerPixel",  // Compressed bits per pixel

        // user information
        0x927C : "MakerNote",               // Any desired information written by the manufacturer
        0x9286 : "UserComment",             // Comments by user

        // related file
        0xA004 : "RelatedSoundFile",        // Name of related sound file

        // date and time
        0x9003 : "DateTimeOriginal",        // Date and time when the original image was generated
        0x9004 : "DateTimeDigitized",       // Date and time when the image was stored digitally
        0x9290 : "SubsecTime",              // Fractions of seconds for DateTime
        0x9291 : "SubsecTimeOriginal",      // Fractions of seconds for DateTimeOriginal
        0x9292 : "SubsecTimeDigitized",     // Fractions of seconds for DateTimeDigitized

        // picture-taking conditions
        0x829A : "ExposureTime",            // Exposure time (in seconds)
        0x829D : "FNumber",                 // F number
        0x8822 : "ExposureProgram",         // Exposure program
        0x8824 : "SpectralSensitivity",     // Spectral sensitivity
        0x8827 : "ISOSpeedRatings",         // ISO speed rating
        0x8828 : "OECF",                    // Optoelectric conversion factor
        0x9201 : "ShutterSpeedValue",       // Shutter speed
        0x9202 : "ApertureValue",           // Lens aperture
        0x9203 : "BrightnessValue",         // Value of brightness
        0x9204 : "ExposureBias",            // Exposure bias
        0x9205 : "MaxApertureValue",        // Smallest F number of lens
        0x9206 : "SubjectDistance",         // Distance to subject in meters
        0x9207 : "MeteringMode",            // Metering mode
        0x9208 : "LightSource",             // Kind of light source
        0x9209 : "Flash",                   // Flash status
        0x9214 : "SubjectArea",             // Location and area of main subject
        0x920A : "FocalLength",             // Focal length of the lens in mm
        0xA20B : "FlashEnergy",             // Strobe energy in BCPS
        0xA20C : "SpatialFrequencyResponse",    //
        0xA20E : "FocalPlaneXResolution",   // Number of pixels in width direction per FocalPlaneResolutionUnit
        0xA20F : "FocalPlaneYResolution",   // Number of pixels in height direction per FocalPlaneResolutionUnit
        0xA210 : "FocalPlaneResolutionUnit",    // Unit for measuring FocalPlaneXResolution and FocalPlaneYResolution
        0xA214 : "SubjectLocation",         // Location of subject in image
        0xA215 : "ExposureIndex",           // Exposure index selected on camera
        0xA217 : "SensingMethod",           // Image sensor type
        0xA300 : "FileSource",              // Image source (3 == DSC)
        0xA301 : "SceneType",               // Scene type (1 == directly photographed)
        0xA302 : "CFAPattern",              // Color filter array geometric pattern
        0xA401 : "CustomRendered",          // Special processing
        0xA402 : "ExposureMode",            // Exposure mode
        0xA403 : "WhiteBalance",            // 1 = auto white balance, 2 = manual
        0xA404 : "DigitalZoomRation",       // Digital zoom ratio
        0xA405 : "FocalLengthIn35mmFilm",   // Equivalent foacl length assuming 35mm film camera (in mm)
        0xA406 : "SceneCaptureType",        // Type of scene
        0xA407 : "GainControl",             // Degree of overall image gain adjustment
        0xA408 : "Contrast",                // Direction of contrast processing applied by camera
        0xA409 : "Saturation",              // Direction of saturation processing applied by camera
        0xA40A : "Sharpness",               // Direction of sharpness processing applied by camera
        0xA40B : "DeviceSettingDescription",    //
        0xA40C : "SubjectDistanceRange",    // Distance to subject

        // other tags
        0xA005 : "InteroperabilityIFDPointer",
        0xA420 : "ImageUniqueID"            // Identifier assigned uniquely to each image
    };

    var TiffTags = EXIF.TiffTags = {
        0x0100 : "ImageWidth",
        0x0101 : "ImageHeight",
        0x8769 : "ExifIFDPointer",
        0x8825 : "GPSInfoIFDPointer",
        0xA005 : "InteroperabilityIFDPointer",
        0x0102 : "BitsPerSample",
        0x0103 : "Compression",
        0x0106 : "PhotometricInterpretation",
        0x0112 : "Orientation",
        0x0115 : "SamplesPerPixel",
        0x011C : "PlanarConfiguration",
        0x0212 : "YCbCrSubSampling",
        0x0213 : "YCbCrPositioning",
        0x011A : "XResolution",
        0x011B : "YResolution",
        0x0128 : "ResolutionUnit",
        0x0111 : "StripOffsets",
        0x0116 : "RowsPerStrip",
        0x0117 : "StripByteCounts",
        0x0201 : "JPEGInterchangeFormat",
        0x0202 : "JPEGInterchangeFormatLength",
        0x012D : "TransferFunction",
        0x013E : "WhitePoint",
        0x013F : "PrimaryChromaticities",
        0x0211 : "YCbCrCoefficients",
        0x0214 : "ReferenceBlackWhite",
        0x0132 : "DateTime",
        0x010E : "ImageDescription",
        0x010F : "Make",
        0x0110 : "Model",
        0x0131 : "Software",
        0x013B : "Artist",
        0x8298 : "Copyright"
    };

    var GPSTags = EXIF.GPSTags = {
        0x0000 : "GPSVersionID",
        0x0001 : "GPSLatitudeRef",
        0x0002 : "GPSLatitude",
        0x0003 : "GPSLongitudeRef",
        0x0004 : "GPSLongitude",
        0x0005 : "GPSAltitudeRef",
        0x0006 : "GPSAltitude",
        0x0007 : "GPSTimeStamp",
        0x0008 : "GPSSatellites",
        0x0009 : "GPSStatus",
        0x000A : "GPSMeasureMode",
        0x000B : "GPSDOP",
        0x000C : "GPSSpeedRef",
        0x000D : "GPSSpeed",
        0x000E : "GPSTrackRef",
        0x000F : "GPSTrack",
        0x0010 : "GPSImgDirectionRef",
        0x0011 : "GPSImgDirection",
        0x0012 : "GPSMapDatum",
        0x0013 : "GPSDestLatitudeRef",
        0x0014 : "GPSDestLatitude",
        0x0015 : "GPSDestLongitudeRef",
        0x0016 : "GPSDestLongitude",
        0x0017 : "GPSDestBearingRef",
        0x0018 : "GPSDestBearing",
        0x0019 : "GPSDestDistanceRef",
        0x001A : "GPSDestDistance",
        0x001B : "GPSProcessingMethod",
        0x001C : "GPSAreaInformation",
        0x001D : "GPSDateStamp",
        0x001E : "GPSDifferential"
    };

     // EXIF 2.3 Spec
    var IFD1Tags = EXIF.IFD1Tags = {
        0x0100: "ImageWidth",
        0x0101: "ImageHeight",
        0x0102: "BitsPerSample",
        0x0103: "Compression",
        0x0106: "PhotometricInterpretation",
        0x0111: "StripOffsets",
        0x0112: "Orientation",
        0x0115: "SamplesPerPixel",
        0x0116: "RowsPerStrip",
        0x0117: "StripByteCounts",
        0x011A: "XResolution",
        0x011B: "YResolution",
        0x011C: "PlanarConfiguration",
        0x0128: "ResolutionUnit",
        0x0201: "JpegIFOffset",    // When image format is JPEG, this value show offset to JPEG data stored.(aka "ThumbnailOffset" or "JPEGInterchangeFormat")
        0x0202: "JpegIFByteCount", // When image format is JPEG, this value shows data size of JPEG image (aka "ThumbnailLength" or "JPEGInterchangeFormatLength")
        0x0211: "YCbCrCoefficients",
        0x0212: "YCbCrSubSampling",
        0x0213: "YCbCrPositioning",
        0x0214: "ReferenceBlackWhite"
    };

    var StringValues = EXIF.StringValues = {
        ExposureProgram : {
            0 : "Not defined",
            1 : "Manual",
            2 : "Normal program",
            3 : "Aperture priority",
            4 : "Shutter priority",
            5 : "Creative program",
            6 : "Action program",
            7 : "Portrait mode",
            8 : "Landscape mode"
        },
        MeteringMode : {
            0 : "Unknown",
            1 : "Average",
            2 : "CenterWeightedAverage",
            3 : "Spot",
            4 : "MultiSpot",
            5 : "Pattern",
            6 : "Partial",
            255 : "Other"
        },
        LightSource : {
            0 : "Unknown",
            1 : "Daylight",
            2 : "Fluorescent",
            3 : "Tungsten (incandescent light)",
            4 : "Flash",
            9 : "Fine weather",
            10 : "Cloudy weather",
            11 : "Shade",
            12 : "Daylight fluorescent (D 5700 - 7100K)",
            13 : "Day white fluorescent (N 4600 - 5400K)",
            14 : "Cool white fluorescent (W 3900 - 4500K)",
            15 : "White fluorescent (WW 3200 - 3700K)",
            17 : "Standard light A",
            18 : "Standard light B",
            19 : "Standard light C",
            20 : "D55",
            21 : "D65",
            22 : "D75",
            23 : "D50",
            24 : "ISO studio tungsten",
            255 : "Other"
        },
        Flash : {
            0x0000 : "Flash did not fire",
            0x0001 : "Flash fired",
            0x0005 : "Strobe return light not detected",
            0x0007 : "Strobe return light detected",
            0x0009 : "Flash fired, compulsory flash mode",
            0x000D : "Flash fired, compulsory flash mode, return light not detected",
            0x000F : "Flash fired, compulsory flash mode, return light detected",
            0x0010 : "Flash did not fire, compulsory flash mode",
            0x0018 : "Flash did not fire, auto mode",
            0x0019 : "Flash fired, auto mode",
            0x001D : "Flash fired, auto mode, return light not detected",
            0x001F : "Flash fired, auto mode, return light detected",
            0x0020 : "No flash function",
            0x0041 : "Flash fired, red-eye reduction mode",
            0x0045 : "Flash fired, red-eye reduction mode, return light not detected",
            0x0047 : "Flash fired, red-eye reduction mode, return light detected",
            0x0049 : "Flash fired, compulsory flash mode, red-eye reduction mode",
            0x004D : "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
            0x004F : "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
            0x0059 : "Flash fired, auto mode, red-eye reduction mode",
            0x005D : "Flash fired, auto mode, return light not detected, red-eye reduction mode",
            0x005F : "Flash fired, auto mode, return light detected, red-eye reduction mode"
        },
        SensingMethod : {
            1 : "Not defined",
            2 : "One-chip color area sensor",
            3 : "Two-chip color area sensor",
            4 : "Three-chip color area sensor",
            5 : "Color sequential area sensor",
            7 : "Trilinear sensor",
            8 : "Color sequential linear sensor"
        },
        SceneCaptureType : {
            0 : "Standard",
            1 : "Landscape",
            2 : "Portrait",
            3 : "Night scene"
        },
        SceneType : {
            1 : "Directly photographed"
        },
        CustomRendered : {
            0 : "Normal process",
            1 : "Custom process"
        },
        WhiteBalance : {
            0 : "Auto white balance",
            1 : "Manual white balance"
        },
        GainControl : {
            0 : "None",
            1 : "Low gain up",
            2 : "High gain up",
            3 : "Low gain down",
            4 : "High gain down"
        },
        Contrast : {
            0 : "Normal",
            1 : "Soft",
            2 : "Hard"
        },
        Saturation : {
            0 : "Normal",
            1 : "Low saturation",
            2 : "High saturation"
        },
        Sharpness : {
            0 : "Normal",
            1 : "Soft",
            2 : "Hard"
        },
        SubjectDistanceRange : {
            0 : "Unknown",
            1 : "Macro",
            2 : "Close view",
            3 : "Distant view"
        },
        FileSource : {
            3 : "DSC"
        },

        Components : {
            0 : "",
            1 : "Y",
            2 : "Cb",
            3 : "Cr",
            4 : "R",
            5 : "G",
            6 : "B"
        }
    };

    function addEvent(element, event, handler) {
        if (element.addEventListener) {
            element.addEventListener(event, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + event, handler);
        }
    }

    function imageHasData(img) {
        return !!(img.exifdata);
    }


    function base64ToArrayBuffer(base64, contentType) {
        contentType = contentType || base64.match(/^data\:([^\;]+)\;base64,/mi)[1] || ''; // e.g. 'data:image/jpeg;base64,...' => 'image/jpeg'
        base64 = base64.replace(/^data\:([^\;]+)\;base64,/gmi, '');
        var binary = atob(base64);
        var len = binary.length;
        var buffer = new ArrayBuffer(len);
        var view = new Uint8Array(buffer);
        for (var i = 0; i < len; i++) {
            view[i] = binary.charCodeAt(i);
        }
        return buffer;
    }

    function objectURLToBlob(url, callback) {
        var http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.responseType = "blob";
        http.onload = function(e) {
            if (this.status == 200 || this.status === 0) {
                callback(this.response);
            }
        };
        http.send();
    }

    function getImageData(img, callback) {
        function handleBinaryFile(binFile) {
            var data = findEXIFinJPEG(binFile);
            img.exifdata = data || {};
            var iptcdata = findIPTCinJPEG(binFile);
            img.iptcdata = iptcdata || {};
            if (EXIF.isXmpEnabled) {
               var xmpdata= findXMPinJPEG(binFile);
               img.xmpdata = xmpdata || {};               
            }
            if (callback) {
                callback.call(img);
            }
        }

        if (img.src) {
            if (/^data\:/i.test(img.src)) { // Data URI
                var arrayBuffer = base64ToArrayBuffer(img.src);
                handleBinaryFile(arrayBuffer);

            } else if (/^blob\:/i.test(img.src)) { // Object URL
                var fileReader = new FileReader();
                fileReader.onload = function(e) {
                    handleBinaryFile(e.target.result);
                };
                objectURLToBlob(img.src, function (blob) {
                    fileReader.readAsArrayBuffer(blob);
                });
            } else {
                var http = new XMLHttpRequest();
                http.onload = function() {
                    if (this.status == 200 || this.status === 0) {
                        handleBinaryFile(http.response);
                    } else {
                        throw "Could not load image";
                    }
                    http = null;
                };
                http.open("GET", img.src, true);
                http.responseType = "arraybuffer";
                http.send(null);
            }
        } else if (self.FileReader && (img instanceof self.Blob || img instanceof self.File)) {
            var fileReader = new FileReader();
            fileReader.onload = function(e) {
                if (debug) console.log("Got file of length " + e.target.result.byteLength);
                handleBinaryFile(e.target.result);
            };

            fileReader.readAsArrayBuffer(img);
        }
    }

    function findEXIFinJPEG(file) {
        var dataView = new DataView(file);

        if (debug) console.log("Got file of length " + file.byteLength);
        if ((dataView.getUint8(0) != 0xFF) || (dataView.getUint8(1) != 0xD8)) {
            if (debug) console.log("Not a valid JPEG");
            return false; // not a valid jpeg
        }

        var offset = 2,
            length = file.byteLength,
            marker;

        while (offset < length) {
            if (dataView.getUint8(offset) != 0xFF) {
                if (debug) console.log("Not a valid marker at offset " + offset + ", found: " + dataView.getUint8(offset));
                return false; // not a valid marker, something is wrong
            }

            marker = dataView.getUint8(offset + 1);
            if (debug) console.log(marker);

            // we could implement handling for other markers here,
            // but we're only looking for 0xFFE1 for EXIF data

            if (marker == 225) {
                if (debug) console.log("Found 0xFFE1 marker");

                return readEXIFData(dataView, offset + 4, dataView.getUint16(offset + 2) - 2);

                // offset += 2 + file.getShortAt(offset+2, true);

            } else {
                offset += 2 + dataView.getUint16(offset+2);
            }

        }

    }

    function findIPTCinJPEG(file) {
        var dataView = new DataView(file);

        if (debug) console.log("Got file of length " + file.byteLength);
        if ((dataView.getUint8(0) != 0xFF) || (dataView.getUint8(1) != 0xD8)) {
            if (debug) console.log("Not a valid JPEG");
            return false; // not a valid jpeg
        }

        var offset = 2,
            length = file.byteLength;


        var isFieldSegmentStart = function(dataView, offset){
            return (
                dataView.getUint8(offset) === 0x38 &&
                dataView.getUint8(offset+1) === 0x42 &&
                dataView.getUint8(offset+2) === 0x49 &&
                dataView.getUint8(offset+3) === 0x4D &&
                dataView.getUint8(offset+4) === 0x04 &&
                dataView.getUint8(offset+5) === 0x04
            );
        };

        while (offset < length) {

            if ( isFieldSegmentStart(dataView, offset )){

                // Get the length of the name header (which is padded to an even number of bytes)
                var nameHeaderLength = dataView.getUint8(offset+7);
                if(nameHeaderLength % 2 !== 0) nameHeaderLength += 1;
                // Check for pre photoshop 6 format
                if(nameHeaderLength === 0) {
                    // Always 4
                    nameHeaderLength = 4;
                }

                var startOffset = offset + 8 + nameHeaderLength;
                var sectionLength = dataView.getUint16(offset + 6 + nameHeaderLength);

                return readIPTCData(file, startOffset, sectionLength);

                break;

            }


            // Not the marker, continue searching
            offset++;

        }

    }
    var IptcFieldMap = {
        0x78 : 'caption',
        0x6E : 'credit',
        0x19 : 'keywords',
        0x37 : 'dateCreated',
        0x50 : 'byline',
        0x55 : 'bylineTitle',
        0x7A : 'captionWriter',
        0x69 : 'headline',
        0x74 : 'copyright',
        0x0F : 'category'
    };
    function readIPTCData(file, startOffset, sectionLength){
        var dataView = new DataView(file);
        var data = {};
        var fieldValue, fieldName, dataSize, segmentType, segmentSize;
        var segmentStartPos = startOffset;
        while(segmentStartPos < startOffset+sectionLength) {
            if(dataView.getUint8(segmentStartPos) === 0x1C && dataView.getUint8(segmentStartPos+1) === 0x02){
                segmentType = dataView.getUint8(segmentStartPos+2);
                if(segmentType in IptcFieldMap) {
                    dataSize = dataView.getInt16(segmentStartPos+3);
                    segmentSize = dataSize + 5;
                    fieldName = IptcFieldMap[segmentType];
                    fieldValue = getStringFromDB(dataView, segmentStartPos+5, dataSize);
                    // Check if we already stored a value with this name
                    if(data.hasOwnProperty(fieldName)) {
                        // Value already stored with this name, create multivalue field
                        if(data[fieldName] instanceof Array) {
                            data[fieldName].push(fieldValue);
                        }
                        else {
                            data[fieldName] = [data[fieldName], fieldValue];
                        }
                    }
                    else {
                        data[fieldName] = fieldValue;
                    }
                }

            }
            segmentStartPos++;
        }
        return data;
    }



    function readTags(file, tiffStart, dirStart, strings, bigEnd) {
        var entries = file.getUint16(dirStart, !bigEnd),
            tags = {},
            entryOffset, tag,
            i;

        for (i=0;i<entries;i++) {
            entryOffset = dirStart + i*12 + 2;
            tag = strings[file.getUint16(entryOffset, !bigEnd)];
            if (!tag && debug) console.log("Unknown tag: " + file.getUint16(entryOffset, !bigEnd));
            tags[tag] = readTagValue(file, entryOffset, tiffStart, dirStart, bigEnd);
        }
        return tags;
    }


    function readTagValue(file, entryOffset, tiffStart, dirStart, bigEnd) {
        var type = file.getUint16(entryOffset+2, !bigEnd),
            numValues = file.getUint32(entryOffset+4, !bigEnd),
            valueOffset = file.getUint32(entryOffset+8, !bigEnd) + tiffStart,
            offset,
            vals, val, n,
            numerator, denominator;

        switch (type) {
            case 1: // byte, 8-bit unsigned int
            case 7: // undefined, 8-bit byte, value depending on field
                if (numValues == 1) {
                    return file.getUint8(entryOffset + 8, !bigEnd);
                } else {
                    offset = numValues > 4 ? valueOffset : (entryOffset + 8);
                    vals = [];
                    for (n=0;n<numValues;n++) {
                        vals[n] = file.getUint8(offset + n);
                    }
                    return vals;
                }

            case 2: // ascii, 8-bit byte
                offset = numValues > 4 ? valueOffset : (entryOffset + 8);
                return getStringFromDB(file, offset, numValues-1);

            case 3: // short, 16 bit int
                if (numValues == 1) {
                    return file.getUint16(entryOffset + 8, !bigEnd);
                } else {
                    offset = numValues > 2 ? valueOffset : (entryOffset + 8);
                    vals = [];
                    for (n=0;n<numValues;n++) {
                        vals[n] = file.getUint16(offset + 2*n, !bigEnd);
                    }
                    return vals;
                }

            case 4: // long, 32 bit int
                if (numValues == 1) {
                    return file.getUint32(entryOffset + 8, !bigEnd);
                } else {
                    vals = [];
                    for (n=0;n<numValues;n++) {
                        vals[n] = file.getUint32(valueOffset + 4*n, !bigEnd);
                    }
                    return vals;
                }

            case 5:    // rational = two long values, first is numerator, second is denominator
                if (numValues == 1) {
                    numerator = file.getUint32(valueOffset, !bigEnd);
                    denominator = file.getUint32(valueOffset+4, !bigEnd);
                    val = new Number(numerator / denominator);
                    val.numerator = numerator;
                    val.denominator = denominator;
                    return val;
                } else {
                    vals = [];
                    for (n=0;n<numValues;n++) {
                        numerator = file.getUint32(valueOffset + 8*n, !bigEnd);
                        denominator = file.getUint32(valueOffset+4 + 8*n, !bigEnd);
                        vals[n] = new Number(numerator / denominator);
                        vals[n].numerator = numerator;
                        vals[n].denominator = denominator;
                    }
                    return vals;
                }

            case 9: // slong, 32 bit signed int
                if (numValues == 1) {
                    return file.getInt32(entryOffset + 8, !bigEnd);
                } else {
                    vals = [];
                    for (n=0;n<numValues;n++) {
                        vals[n] = file.getInt32(valueOffset + 4*n, !bigEnd);
                    }
                    return vals;
                }

            case 10: // signed rational, two slongs, first is numerator, second is denominator
                if (numValues == 1) {
                    return file.getInt32(valueOffset, !bigEnd) / file.getInt32(valueOffset+4, !bigEnd);
                } else {
                    vals = [];
                    for (n=0;n<numValues;n++) {
                        vals[n] = file.getInt32(valueOffset + 8*n, !bigEnd) / file.getInt32(valueOffset+4 + 8*n, !bigEnd);
                    }
                    return vals;
                }
        }
    }

    /**
    * Given an IFD (Image File Directory) start offset
    * returns an offset to next IFD or 0 if it's the last IFD.
    */
    function getNextIFDOffset(dataView, dirStart, bigEnd){
        //the first 2bytes means the number of directory entries contains in this IFD
        var entries = dataView.getUint16(dirStart, !bigEnd);

        // After last directory entry, there is a 4bytes of data,
        // it means an offset to next IFD.
        // If its value is '0x00000000', it means this is the last IFD and there is no linked IFD.

        return dataView.getUint32(dirStart + 2 + entries * 12, !bigEnd); // each entry is 12 bytes long
    }

    function readThumbnailImage(dataView, tiffStart, firstIFDOffset, bigEnd){
        // get the IFD1 offset
        var IFD1OffsetPointer = getNextIFDOffset(dataView, tiffStart+firstIFDOffset, bigEnd);

        if (!IFD1OffsetPointer) {
            // console.log('******** IFD1Offset is empty, image thumb not found ********');
            return {};
        }
        else if (IFD1OffsetPointer > dataView.byteLength) { // this should not happen
            // console.log('******** IFD1Offset is outside the bounds of the DataView ********');
            return {};
        }
        // console.log('*******  thumbnail IFD offset (IFD1) is: %s', IFD1OffsetPointer);

        var thumbTags = readTags(dataView, tiffStart, tiffStart + IFD1OffsetPointer, IFD1Tags, bigEnd)

        // EXIF 2.3 specification for JPEG format thumbnail

        // If the value of Compression(0x0103) Tag in IFD1 is '6', thumbnail image format is JPEG.
        // Most of Exif image uses JPEG format for thumbnail. In that case, you can get offset of thumbnail
        // by JpegIFOffset(0x0201) Tag in IFD1, size of thumbnail by JpegIFByteCount(0x0202) Tag.
        // Data format is ordinary JPEG format, starts from 0xFFD8 and ends by 0xFFD9. It seems that
        // JPEG format and 160x120pixels of size are recommended thumbnail format for Exif2.1 or later.

        if (thumbTags['Compression']) {
            // console.log('Thumbnail image found!');

            switch (thumbTags['Compression']) {
                case 6:
                    // console.log('Thumbnail image format is JPEG');
                    if (thumbTags.JpegIFOffset && thumbTags.JpegIFByteCount) {
                    // extract the thumbnail
                        var tOffset = tiffStart + thumbTags.JpegIFOffset;
                        var tLength = thumbTags.JpegIFByteCount;
                        thumbTags['blob'] = new Blob([new Uint8Array(dataView.buffer, tOffset, tLength)], {
                            type: 'image/jpeg'
                        });
                    }
                break;

            case 1:
                console.log("Thumbnail image format is TIFF, which is not implemented.");
                break;
            default:
                console.log("Unknown thumbnail image format '%s'", thumbTags['Compression']);
            }
        }
        else if (thumbTags['PhotometricInterpretation'] == 2) {
            console.log("Thumbnail image format is RGB, which is not implemented.");
        }
        return thumbTags;
    }

    function getStringFromDB(buffer, start, length) {
        var outstr = "";
        for (var n = start; n < start+length; n++) {
            outstr += String.fromCharCode(buffer.getUint8(n));
        }
        return outstr;
    }

    function readEXIFData(file, start) {
        if (getStringFromDB(file, start, 4) != "Exif") {
            if (debug) console.log("Not valid EXIF data! " + getStringFromDB(file, start, 4));
            return false;
        }

        var bigEnd,
            tags, tag,
            exifData, gpsData,
            tiffOffset = start + 6;

        // test for TIFF validity and endianness
        if (file.getUint16(tiffOffset) == 0x4949) {
            bigEnd = false;
        } else if (file.getUint16(tiffOffset) == 0x4D4D) {
            bigEnd = true;
        } else {
            if (debug) console.log("Not valid TIFF data! (no 0x4949 or 0x4D4D)");
            return false;
        }

        if (file.getUint16(tiffOffset+2, !bigEnd) != 0x002A) {
            if (debug) console.log("Not valid TIFF data! (no 0x002A)");
            return false;
        }

        var firstIFDOffset = file.getUint32(tiffOffset+4, !bigEnd);

        if (firstIFDOffset < 0x00000008) {
            if (debug) console.log("Not valid TIFF data! (First offset less than 8)", file.getUint32(tiffOffset+4, !bigEnd));
            return false;
        }

        tags = readTags(file, tiffOffset, tiffOffset + firstIFDOffset, TiffTags, bigEnd);

        if (tags.ExifIFDPointer) {
            exifData = readTags(file, tiffOffset, tiffOffset + tags.ExifIFDPointer, ExifTags, bigEnd);
            for (tag in exifData) {
                switch (tag) {
                    case "LightSource" :
                    case "Flash" :
                    case "MeteringMode" :
                    case "ExposureProgram" :
                    case "SensingMethod" :
                    case "SceneCaptureType" :
                    case "SceneType" :
                    case "CustomRendered" :
                    case "WhiteBalance" :
                    case "GainControl" :
                    case "Contrast" :
                    case "Saturation" :
                    case "Sharpness" :
                    case "SubjectDistanceRange" :
                    case "FileSource" :
                        exifData[tag] = StringValues[tag][exifData[tag]];
                        break;

                    case "ExifVersion" :
                    case "FlashpixVersion" :
                        exifData[tag] = String.fromCharCode(exifData[tag][0], exifData[tag][1], exifData[tag][2], exifData[tag][3]);
                        break;

                    case "ComponentsConfiguration" :
                        exifData[tag] =
                            StringValues.Components[exifData[tag][0]] +
                            StringValues.Components[exifData[tag][1]] +
                            StringValues.Components[exifData[tag][2]] +
                            StringValues.Components[exifData[tag][3]];
                        break;
                }
                tags[tag] = exifData[tag];
            }
        }

        if (tags.GPSInfoIFDPointer) {
            gpsData = readTags(file, tiffOffset, tiffOffset + tags.GPSInfoIFDPointer, GPSTags, bigEnd);
            for (tag in gpsData) {
                switch (tag) {
                    case "GPSVersionID" :
                        gpsData[tag] = gpsData[tag][0] +
                            "." + gpsData[tag][1] +
                            "." + gpsData[tag][2] +
                            "." + gpsData[tag][3];
                        break;
                }
                tags[tag] = gpsData[tag];
            }
        }

        // extract thumbnail
        tags['thumbnail'] = readThumbnailImage(file, tiffOffset, firstIFDOffset, bigEnd);

        return tags;
    }

   function findXMPinJPEG(file) {

        if (!('DOMParser' in self)) {
            // console.warn('XML parsing not supported without DOMParser');
            return;
        }
        var dataView = new DataView(file);

        if (debug) console.log("Got file of length " + file.byteLength);
        if ((dataView.getUint8(0) != 0xFF) || (dataView.getUint8(1) != 0xD8)) {
           if (debug) console.log("Not a valid JPEG");
           return false; // not a valid jpeg
        }

        var offset = 2,
            length = file.byteLength,
            dom = new DOMParser();

        while (offset < (length-4)) {
            if (getStringFromDB(dataView, offset, 4) == "http") {
                var startOffset = offset - 1;
                var sectionLength = dataView.getUint16(offset - 2) - 1;
                var xmpString = getStringFromDB(dataView, startOffset, sectionLength)
                var xmpEndIndex = xmpString.indexOf('xmpmeta>') + 8;
                xmpString = xmpString.substring( xmpString.indexOf( '<x:xmpmeta' ), xmpEndIndex );

                var indexOfXmp = xmpString.indexOf('x:xmpmeta') + 10
                //Many custom written programs embed xmp/xml without any namespace. Following are some of them.
                //Without these namespaces, XML is thought to be invalid by parsers
                xmpString = xmpString.slice(0, indexOfXmp)
                            + 'xmlns:Iptc4xmpCore="http://iptc.org/std/Iptc4xmpCore/1.0/xmlns/" '
                            + 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                            + 'xmlns:tiff="http://ns.adobe.com/tiff/1.0/" '
                            + 'xmlns:plus="http://schemas.android.com/apk/lib/com.google.android.gms.plus" '
                            + 'xmlns:ext="http://www.gettyimages.com/xsltExtension/1.0" '
                            + 'xmlns:exif="http://ns.adobe.com/exif/1.0/" '
                            + 'xmlns:stEvt="http://ns.adobe.com/xap/1.0/sType/ResourceEvent#" '
                            + 'xmlns:stRef="http://ns.adobe.com/xap/1.0/sType/ResourceRef#" '
                            + 'xmlns:crs="http://ns.adobe.com/camera-raw-settings/1.0/" '
                            + 'xmlns:xapGImg="http://ns.adobe.com/xap/1.0/g/img/" '
                            + 'xmlns:Iptc4xmpExt="http://iptc.org/std/Iptc4xmpExt/2008-02-29/" '
                            + xmpString.slice(indexOfXmp)

                var domDocument = dom.parseFromString( xmpString, 'text/xml' );
                return xml2Object(domDocument);
            } else{
             offset++;
            }
        }
    }

    function xml2json(xml) {
        var json = {};
      
        if (xml.nodeType == 1) { // element node
          if (xml.attributes.length > 0) {
            json['@attributes'] = {};
            for (var j = 0; j < xml.attributes.length; j++) {
              var attribute = xml.attributes.item(j);
              json['@attributes'][attribute.nodeName] = attribute.nodeValue;
            }
          }
        } else if (xml.nodeType == 3) { // text node
          return xml.nodeValue;
        }
      
        // deal with children
        if (xml.hasChildNodes()) {
          for(var i = 0; i < xml.childNodes.length; i++) {
            var child = xml.childNodes.item(i);
            var nodeName = child.nodeName;
            if (json[nodeName] == null) {
              json[nodeName] = xml2json(child);
            } else {
              if (json[nodeName].push == null) {
                var old = json[nodeName];
                json[nodeName] = [];
                json[nodeName].push(old);
              }
              json[nodeName].push(xml2json(child));
            }
          }
        }
        
        return json;
    }

    function xml2Object(xml) {
        try {
            var obj = {};
            if (xml.children.length > 0) {
              for (var i = 0; i < xml.children.length; i++) {
                var item = xml.children.item(i);
                var attributes = item.attributes;
                for(var idx in attributes) {
                    var itemAtt = attributes[idx];
                    var dataKey = itemAtt.nodeName;
                    var dataValue = itemAtt.nodeValue;

                    if(dataKey !== undefined) {
                        obj[dataKey] = dataValue;
                    }
                }
                var nodeName = item.nodeName;

                if (typeof (obj[nodeName]) == "undefined") {
                  obj[nodeName] = xml2json(item);
                } else {
                  if (typeof (obj[nodeName].push) == "undefined") {
                    var old = obj[nodeName];

                    obj[nodeName] = [];
                    obj[nodeName].push(old);
                  }
                  obj[nodeName].push(xml2json(item));
                }
              }
            } else {
              obj = xml.textContent;
            }
            return obj;
          } catch (e) {
              console.log(e.message);
          }
    }

    EXIF.enableXmp = function() {
        EXIF.isXmpEnabled = true;
    }

    EXIF.disableXmp = function() {
        EXIF.isXmpEnabled = false;
    }

    EXIF.getData = function(img, callback) {
        if (((self.Image && img instanceof self.Image)
            || (self.HTMLImageElement && img instanceof self.HTMLImageElement))
            && !img.complete)
            return false;

        if (!imageHasData(img)) {
            getImageData(img, callback);
        } else {
            if (callback) {
                callback.call(img);
            }
        }
        return true;
    }

    EXIF.getTag = function(img, tag) {
        if (!imageHasData(img)) return;
        return img.exifdata[tag];
    }
    
    EXIF.getIptcTag = function(img, tag) {
        if (!imageHasData(img)) return;
        return img.iptcdata[tag];
    }

    EXIF.getAllTags = function(img) {
        if (!imageHasData(img)) return {};
        var a,
            data = img.exifdata,
            tags = {};
        for (a in data) {
            if (data.hasOwnProperty(a)) {
                tags[a] = data[a];
            }
        }
        return tags;
    }
    
    EXIF.getAllIptcTags = function(img) {
        if (!imageHasData(img)) return {};
        var a,
            data = img.iptcdata,
            tags = {};
        for (a in data) {
            if (data.hasOwnProperty(a)) {
                tags[a] = data[a];
            }
        }
        return tags;
    }

    EXIF.pretty = function(img) {
        if (!imageHasData(img)) return "";
        var a,
            data = img.exifdata,
            strPretty = "";
        for (a in data) {
            if (data.hasOwnProperty(a)) {
                if (typeof data[a] == "object") {
                    if (data[a] instanceof Number) {
                        strPretty += a + " : " + data[a] + " [" + data[a].numerator + "/" + data[a].denominator + "]\r\n";
                    } else {
                        strPretty += a + " : [" + data[a].length + " values]\r\n";
                    }
                } else {
                    strPretty += a + " : " + data[a] + "\r\n";
                }
            }
        }
        return strPretty;
    }

    EXIF.readFromBinaryFile = function(file) {
        return findEXIFinJPEG(file);
    }

    if (typeof define === 'function' && define.amd) {
        define('exif-js', [], function() {
            return EXIF;
        });
    }
}.call(this));

/*************************
 * Croppie
 * Copyright 2019
 * Foliotek
 * Version: 2.6.5
 *************************/
(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(factory);
    } else if (typeof exports === 'object' && typeof exports.nodeName !== 'string') {
        // CommonJS
        module.exports = factory();
    } else {
        // Browser globals
        root.Croppie = factory();
    }
}(typeof self !== 'undefined' ? self : this, function () {

    /* Polyfills */
    if (typeof Promise !== 'function') {
        /*! promise-polyfill 3.1.0 */
        !function(a){function b(a,b){return function(){a.apply(b,arguments)}}function c(a){if("object"!==typeof this)throw new TypeError("Promises must be constructed via new");if("function"!==typeof a)throw new TypeError("not a function");this._state=null,this._value=null,this._deferreds=[],i(a,b(e,this),b(f,this))}function d(a){var b=this;return null===this._state?void this._deferreds.push(a):void k(function(){var c=b._state?a.onFulfilled:a.onRejected;if(null===c)return void(b._state?a.resolve:a.reject)(b._value);var d;try{d=c(b._value)}catch(e){return void a.reject(e)}a.resolve(d)})}function e(a){try{if(a===this)throw new TypeError("A promise cannot be resolved with itself.");if(a&&("object"===typeof a||"function"===typeof a)){var c=a.then;if("function"===typeof c)return void i(b(c,a),b(e,this),b(f,this))}this._state=!0,this._value=a,g.call(this)}catch(d){f.call(this,d)}}function f(a){this._state=!1,this._value=a,g.call(this)}function g(){for(var a=0,b=this._deferreds.length;b>a;a++)d.call(this,this._deferreds[a]);this._deferreds=null}function h(a,b,c,d){this.onFulfilled="function"===typeof a?a:null,this.onRejected="function"===typeof b?b:null,this.resolve=c,this.reject=d}function i(a,b,c){var d=!1;try{a(function(a){d||(d=!0,b(a))},function(a){d||(d=!0,c(a))})}catch(e){if(d)return;d=!0,c(e)}}var j=setTimeout,k="function"===typeof setImmediate&&setImmediate||function(a){j(a,1)},l=Array.isArray||function(a){return"[object Array]"===Object.prototype.toString.call(a)};c.prototype["catch"]=function(a){return this.then(null,a)},c.prototype.then=function(a,b){var e=this;return new c(function(c,f){d.call(e,new h(a,b,c,f))})},c.all=function(){var a=Array.prototype.slice.call(1===arguments.length&&l(arguments[0])?arguments[0]:arguments);return new c(function(b,c){function d(f,g){try{if(g&&("object"===typeof g||"function"===typeof g)){var h=g.then;if("function"===typeof h)return void h.call(g,function(a){d(f,a)},c)}a[f]=g,0===--e&&b(a)}catch(i){c(i)}}if(0===a.length)return b([]);for(var e=a.length,f=0;f<a.length;f++)d(f,a[f])})},c.resolve=function(a){return a&&"object"===typeof a&&a.constructor===c?a:new c(function(b){b(a)})},c.reject=function(a){return new c(function(b,c){c(a)})},c.race=function(a){return new c(function(b,c){for(var d=0,e=a.length;e>d;d++)a[d].then(b,c)})},c._setImmediateFn=function(a){k=a},"undefined"!==typeof module&&module.exports?module.exports=c:a.Promise||(a.Promise=c)}(this);
    }

    if (typeof window !== 'undefined' && typeof window.CustomEvent !== "function") {
        (function(){
            function CustomEvent ( event, params ) {
                params = params || { bubbles: false, cancelable: false, detail: undefined };
                var evt = document.createEvent( 'CustomEvent' );
                evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
                return evt;
            }
            CustomEvent.prototype = window.Event.prototype;
            window.CustomEvent = CustomEvent;
        }());
    }

    if (typeof HTMLCanvasElement !== 'undefined' && !HTMLCanvasElement.prototype.toBlob) {
        Object.defineProperty(HTMLCanvasElement.prototype, 'toBlob', {
            value: function (callback, type, quality) {
                var binStr = atob( this.toDataURL(type, quality).split(',')[1] ),
                len = binStr.length,
                arr = new Uint8Array(len);

                for (var i=0; i<len; i++ ) {
                    arr[i] = binStr.charCodeAt(i);
                }

                callback( new Blob( [arr], {type: type || 'image/png'} ) );
            }
        });
    }
    /* End Polyfills */

    var cssPrefixes = ['Webkit', 'Moz', 'ms'],
        emptyStyles = typeof document !== 'undefined' ? document.createElement('div').style : {},
        EXIF_NORM = [1,8,3,6],
        EXIF_FLIP = [2,7,4,5],
        CSS_TRANS_ORG,
        CSS_TRANSFORM,
        CSS_USERSELECT;

    function vendorPrefix(prop) {
        if (prop in emptyStyles) {
            return prop;
        }

        var capProp = prop[0].toUpperCase() + prop.slice(1),
            i = cssPrefixes.length;

        while (i--) {
            prop = cssPrefixes[i] + capProp;
            if (prop in emptyStyles) {
                return prop;
            }
        }
    }

    CSS_TRANSFORM = vendorPrefix('transform');
    CSS_TRANS_ORG = vendorPrefix('transformOrigin');
    CSS_USERSELECT = vendorPrefix('userSelect');

    function getExifOffset(ornt, rotate) {
        var arr = EXIF_NORM.indexOf(ornt) > -1 ? EXIF_NORM : EXIF_FLIP,
            index = arr.indexOf(ornt),
            offset = (rotate / 90) % arr.length;// 180 = 2%4 = 2 shift exif by 2 indexes

        return arr[(arr.length + index + (offset % arr.length)) % arr.length];
    }

    // Credits to : Andrew Dupont - http://andrewdupont.net/2009/08/28/deep-extending-objects-in-javascript/
    function deepExtend(destination, source) {
        destination = destination || {};
        for (var property in source) {
            if (source[property] && source[property].constructor && source[property].constructor === Object) {
                destination[property] = destination[property] || {};
                deepExtend(destination[property], source[property]);
            } else {
                destination[property] = source[property];
            }
        }
        return destination;
    }

    function clone(object) {
        return deepExtend({}, object);
    }

    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    function dispatchChange(element) {
        if ("createEvent" in document) {
            var evt = document.createEvent("HTMLEvents");
            evt.initEvent("change", false, true);
            element.dispatchEvent(evt);
        }
        else {
            element.fireEvent("onchange");
        }
    }

    //http://jsperf.com/vanilla-css
    function css(el, styles, val) {
        if (typeof (styles) === 'string') {
            var tmp = styles;
            styles = {};
            styles[tmp] = val;
        }

        for (var prop in styles) {
            el.style[prop] = styles[prop];
        }
    }

    function addClass(el, c) {
        if (el.classList) {
            el.classList.add(c);
        }
        else {
            el.className += ' ' + c;
        }
    }

    function removeClass(el, c) {
        if (el.classList) {
            el.classList.remove(c);
        }
        else {
            el.className = el.className.replace(c, '');
        }
    }

    function setAttributes(el, attrs) {
        for (var key in attrs) {
            el.setAttribute(key, attrs[key]);
        }
    }

    function num(v) {
        return parseInt(v, 10);
    }

    /* Utilities */
    function loadImage(src, doExif) {
        if (!src) { throw 'Source image missing'; }
        
        var img = new Image();
        img.style.opacity = '0';
        return new Promise(function (resolve, reject) {
            function _resolve() {
                img.style.opacity = '1';
                setTimeout(function () {
                    resolve(img);
                }, 1);
            }

            img.removeAttribute('crossOrigin');
            if (src.match(/^https?:\/\/|^\/\//)) {
                img.setAttribute('crossOrigin', 'anonymous');
            }

            img.onload = function () {
                if (doExif) {
                    EXIF.getData(img, function () {
                        _resolve();
                    });
                }
                else {
                    _resolve();
                }
            };
            img.onerror = function (ev) {
                img.style.opacity = 1;
                setTimeout(function () {
                    reject(ev);
                }, 1);
            };
            img.src = src;
        });
    }

    function naturalImageDimensions(img, ornt) {
        var w = img.naturalWidth;
        var h = img.naturalHeight;
        var orient = ornt || getExifOrientation(img);
        if (orient && orient >= 5) {
            var x= w;
            w = h;
            h = x;
        }
        return { width: w, height: h };
    }

    /* CSS Transform Prototype */
    var TRANSLATE_OPTS = {
        'translate3d': {
            suffix: ', 0px'
        },
        'translate': {
            suffix: ''
        }
    };
    var Transform = function (x, y, scale) {
        this.x = parseFloat(x);
        this.y = parseFloat(y);
        this.scale = parseFloat(scale);
    };

    Transform.parse = function (v) {
        if (v.style) {
            return Transform.parse(v.style[CSS_TRANSFORM]);
        }
        else if (v.indexOf('matrix') > -1 || v.indexOf('none') > -1) {
            return Transform.fromMatrix(v);
        }
        else {
            return Transform.fromString(v);
        }
    };

    Transform.fromMatrix = function (v) {
        var vals = v.substring(7).split(',');
        if (!vals.length || v === 'none') {
            vals = [1, 0, 0, 1, 0, 0];
        }

        return new Transform(num(vals[4]), num(vals[5]), parseFloat(vals[0]));
    };

    Transform.fromString = function (v) {
        var values = v.split(') '),
            translate = values[0].substring(Croppie.globals.translate.length + 1).split(','),
            scale = values.length > 1 ? values[1].substring(6) : 1,
            x = translate.length > 1 ? translate[0] : 0,
            y = translate.length > 1 ? translate[1] : 0;

        return new Transform(x, y, scale);
    };

    Transform.prototype.toString = function () {
        var suffix = TRANSLATE_OPTS[Croppie.globals.translate].suffix || '';
        return Croppie.globals.translate + '(' + this.x + 'px, ' + this.y + 'px' + suffix + ') scale(' + this.scale + ')';
    };

    var TransformOrigin = function (el) {
        if (!el || !el.style[CSS_TRANS_ORG]) {
            this.x = 0;
            this.y = 0;
            return;
        }
        var css = el.style[CSS_TRANS_ORG].split(' ');
        this.x = parseFloat(css[0]);
        this.y = parseFloat(css[1]);
    };

    TransformOrigin.prototype.toString = function () {
        return this.x + 'px ' + this.y + 'px';
    };

    function getExifOrientation (img) {
        return img.exifdata && img.exifdata.Orientation ? num(img.exifdata.Orientation) : 1;
    }

    function drawCanvas(canvas, img, orientation) {
        var width = img.width,
            height = img.height,
            ctx = canvas.getContext('2d');

        canvas.width = img.width;
        canvas.height = img.height;

        ctx.save();
        switch (orientation) {
          case 2:
             ctx.translate(width, 0);
             ctx.scale(-1, 1);
             break;

          case 3:
              ctx.translate(width, height);
              ctx.rotate(180*Math.PI/180);
              break;

          case 4:
              ctx.translate(0, height);
              ctx.scale(1, -1);
              break;

          case 5:
              canvas.width = height;
              canvas.height = width;
              ctx.rotate(90*Math.PI/180);
              ctx.scale(1, -1);
              break;

          case 6:
              canvas.width = height;
              canvas.height = width;
              ctx.rotate(90*Math.PI/180);
              ctx.translate(0, -height);
              break;

          case 7:
              canvas.width = height;
              canvas.height = width;
              ctx.rotate(-90*Math.PI/180);
              ctx.translate(-width, height);
              ctx.scale(1, -1);
              break;

          case 8:
              canvas.width = height;
              canvas.height = width;
              ctx.translate(0, width);
              ctx.rotate(-90*Math.PI/180);
              break;
        }
        ctx.drawImage(img, 0,0, width, height);
        ctx.restore();
    }

    /* Private Methods */
    function _create() {
        var self = this,
            contClass = 'croppie-container',
            customViewportClass = self.options.viewport.type ? 'cr-vp-' + self.options.viewport.type : null,
            boundary, img, viewport, overlay, bw, bh;

        self.options.useCanvas = self.options.enableOrientation || _hasExif.call(self);
        // Properties on class
        self.data = {};
        self.elements = {};

        boundary = self.elements.boundary = document.createElement('div');
        viewport = self.elements.viewport = document.createElement('div');
        img = self.elements.img = document.createElement('img');
        overlay = self.elements.overlay = document.createElement('div');

        if (self.options.useCanvas) {
            self.elements.canvas = document.createElement('canvas');
            self.elements.preview = self.elements.canvas;
        }
        else {
            self.elements.preview = img;
        }

        addClass(boundary, 'cr-boundary');
        boundary.setAttribute('aria-dropeffect', 'none');
        bw = self.options.boundary.width;
        bh = self.options.boundary.height;
        css(boundary, {
            width: (bw + (isNaN(bw) ? '' : 'px')),
            height: (bh + (isNaN(bh) ? '' : 'px'))
        });

        addClass(viewport, 'cr-viewport');
        if (customViewportClass) {
            addClass(viewport, customViewportClass);
        }
        css(viewport, {
            width: self.options.viewport.width + 'px',
            height: self.options.viewport.height + 'px'
        });
        viewport.setAttribute('tabindex', 0);

        addClass(self.elements.preview, 'cr-image');
        setAttributes(self.elements.preview, { 'alt': 'preview', 'aria-grabbed': 'false' });
        addClass(overlay, 'cr-overlay');

        self.element.appendChild(boundary);
        boundary.appendChild(self.elements.preview);
        boundary.appendChild(viewport);
        boundary.appendChild(overlay);

        addClass(self.element, contClass);
        if (self.options.customClass) {
            addClass(self.element, self.options.customClass);
        }

        _initDraggable.call(this);

        if (self.options.enableZoom) {
            _initializeZoom.call(self);
        }

        // if (self.options.enableOrientation) {
        //     _initRotationControls.call(self);
        // }

        if (self.options.enableResize) {
            _initializeResize.call(self);
        }
    }

    // function _initRotationControls () {
    //     var self = this,
    //         wrap, btnLeft, btnRight, iLeft, iRight;

    //     wrap = document.createElement('div');
    //     self.elements.orientationBtnLeft = btnLeft = document.createElement('button');
    //     self.elements.orientationBtnRight = btnRight = document.createElement('button');

    //     wrap.appendChild(btnLeft);
    //     wrap.appendChild(btnRight);

    //     iLeft = document.createElement('i');
    //     iRight = document.createElement('i');
    //     btnLeft.appendChild(iLeft);
    //     btnRight.appendChild(iRight);

    //     addClass(wrap, 'cr-rotate-controls');
    //     addClass(btnLeft, 'cr-rotate-l');
    //     addClass(btnRight, 'cr-rotate-r');

    //     self.elements.boundary.appendChild(wrap);

    //     btnLeft.addEventListener('click', function () {
    //         self.rotate(-90);
    //     });
    //     btnRight.addEventListener('click', function () {
    //         self.rotate(90);
    //     });
    // }

    function _hasExif() {
        return this.options.enableExif && window.EXIF;
    }

    function _initializeResize () {
        var self = this;
        var wrap = document.createElement('div');
        var isDragging = false;
        var direction;
        var originalX;
        var originalY;
        var minSize = 50;
        var maxWidth;
        var maxHeight;
        var vr;
        var hr;

        addClass(wrap, 'cr-resizer');
        css(wrap, {
            width: this.options.viewport.width + 'px',
            height: this.options.viewport.height + 'px'
        });

        if (this.options.resizeControls.height) {
            vr = document.createElement('div');
            addClass(vr, 'cr-resizer-vertical');
            wrap.appendChild(vr);
        }

        if (this.options.resizeControls.width) {
            hr = document.createElement('div');
            addClass(hr, 'cr-resizer-horisontal');
            wrap.appendChild(hr);
        }

        function mouseDown(ev) {
            if (ev.button !== undefined && ev.button !== 0) return;

            ev.preventDefault();
            if (isDragging) {
                return;
            }

            var overlayRect = self.elements.overlay.getBoundingClientRect();

            isDragging = true;
            originalX = ev.pageX;
            originalY = ev.pageY;
            direction = ev.currentTarget.className.indexOf('vertical') !== -1 ? 'v' : 'h';
            maxWidth = overlayRect.width;
            maxHeight = overlayRect.height;

            if (ev.touches) {
                var touches = ev.touches[0];
                originalX = touches.pageX;
                originalY = touches.pageY;
            }

            window.addEventListener('mousemove', mouseMove);
            window.addEventListener('touchmove', mouseMove);
            window.addEventListener('mouseup', mouseUp);
            window.addEventListener('touchend', mouseUp);
            document.body.style[CSS_USERSELECT] = 'none';
        }

        function mouseMove(ev) {
            var pageX = ev.pageX;
            var pageY = ev.pageY;

            ev.preventDefault();

            if (ev.touches) {
                var touches = ev.touches[0];
                pageX = touches.pageX;
                pageY = touches.pageY;
            }

            var deltaX = pageX - originalX;
            var deltaY = pageY - originalY;
            var newHeight = self.options.viewport.height + deltaY;
            var newWidth = self.options.viewport.width + deltaX;

            if (direction === 'v' && newHeight >= minSize && newHeight <= maxHeight) {
                css(wrap, {
                    height: newHeight + 'px'
                });

                self.options.boundary.height += deltaY;
                css(self.elements.boundary, {
                    height: self.options.boundary.height + 'px'
                });

                self.options.viewport.height += deltaY;
                css(self.elements.viewport, {
                    height: self.options.viewport.height + 'px'
                });
            }
            else if (direction === 'h' && newWidth >= minSize && newWidth <= maxWidth) {
                css(wrap, {
                    width: newWidth + 'px'
                });

                self.options.boundary.width += deltaX;
                css(self.elements.boundary, {
                    width: self.options.boundary.width + 'px'
                });

                self.options.viewport.width += deltaX;
                css(self.elements.viewport, {
                    width: self.options.viewport.width + 'px'
                });
            }

            _updateOverlay.call(self);
            _updateZoomLimits.call(self);
            _updateCenterPoint.call(self);
            _triggerUpdate.call(self);
            originalY = pageY;
            originalX = pageX;
        }

        function mouseUp() {
            isDragging = false;
            window.removeEventListener('mousemove', mouseMove);
            window.removeEventListener('touchmove', mouseMove);
            window.removeEventListener('mouseup', mouseUp);
            window.removeEventListener('touchend', mouseUp);
            document.body.style[CSS_USERSELECT] = '';
        }

        if (vr) {
            vr.addEventListener('mousedown', mouseDown);
            vr.addEventListener('touchstart', mouseDown);
        }

        if (hr) {
            hr.addEventListener('mousedown', mouseDown);
            hr.addEventListener('touchstart', mouseDown);
        }

        this.elements.boundary.appendChild(wrap);
    }

    function _setZoomerVal(v) {
        if (this.options.enableZoom) {
            var z = this.elements.zoomer,
                val = fix(v, 4);

            z.value = Math.max(parseFloat(z.min), Math.min(parseFloat(z.max), val)).toString();
        }
    }

    function _initializeZoom() {
        var self = this,
            wrap = self.elements.zoomerWrap = document.createElement('div'),
            zoomer = self.elements.zoomer = document.createElement('input');

        addClass(wrap, 'cr-slider-wrap');
        addClass(zoomer, 'cr-slider');
        zoomer.type = 'range';
        zoomer.step = '0.0001';
        zoomer.value = '1';
        zoomer.style.display = self.options.showZoomer ? '' : 'none';
        zoomer.setAttribute('aria-label', 'zoom');

        self.element.appendChild(wrap);
        wrap.appendChild(zoomer);

        self._currentZoom = 1;

        function change() {
            _onZoom.call(self, {
                value: parseFloat(zoomer.value),
                origin: new TransformOrigin(self.elements.preview),
                viewportRect: self.elements.viewport.getBoundingClientRect(),
                transform: Transform.parse(self.elements.preview)
            });
        }

        function scroll(ev) {
            var delta, targetZoom;

            if(self.options.mouseWheelZoom === 'ctrl' && ev.ctrlKey !== true){
              return 0;
            } else if (ev.wheelDelta) {
                delta = ev.wheelDelta / 1200; //wheelDelta min: -120 max: 120 // max x 10 x 2
            } else if (ev.deltaY) {
                delta = ev.deltaY / 1060; //deltaY min: -53 max: 53 // max x 10 x 2
            } else if (ev.detail) {
                delta = ev.detail / -60; //delta min: -3 max: 3 // max x 10 x 2
            } else {
                delta = 0;
            }

            targetZoom = self._currentZoom + (delta * self._currentZoom);

            ev.preventDefault();
            _setZoomerVal.call(self, targetZoom);
            change.call(self);
        }

        self.elements.zoomer.addEventListener('input', change);// this is being fired twice on keypress
        self.elements.zoomer.addEventListener('change', change);

        if (self.options.mouseWheelZoom) {
            self.elements.boundary.addEventListener('mousewheel', scroll);
            self.elements.boundary.addEventListener('DOMMouseScroll', scroll);
        }
    }

    function _onZoom(ui) {
        var self = this,
            transform = ui ? ui.transform : Transform.parse(self.elements.preview),
            vpRect = ui ? ui.viewportRect : self.elements.viewport.getBoundingClientRect(),
            origin = ui ? ui.origin : new TransformOrigin(self.elements.preview);

        function applyCss() {
            var transCss = {};
            transCss[CSS_TRANSFORM] = transform.toString();
            transCss[CSS_TRANS_ORG] = origin.toString();
            css(self.elements.preview, transCss);
        }

        self._currentZoom = ui ? ui.value : self._currentZoom;
        transform.scale = self._currentZoom;
        self.elements.zoomer.setAttribute('aria-valuenow', self._currentZoom);
        applyCss();

        if (self.options.enforceBoundary) {
            var boundaries = _getVirtualBoundaries.call(self, vpRect),
                transBoundaries = boundaries.translate,
                oBoundaries = boundaries.origin;

              if (transform.x >= transBoundaries.maxX) {
                  origin.x = oBoundaries.minX;
                  transform.x = transBoundaries.maxX;
              }
              if (transform.x <= transBoundaries.minX) {
                  origin.x = oBoundaries.maxX;
                  transform.x = transBoundaries.minX;
              }
              if (transform.y >= transBoundaries.maxY) {
                  origin.y = oBoundaries.minY;
                  transform.y = transBoundaries.maxY;
              }
              if (transform.y <= transBoundaries.minY) {
                  origin.y = oBoundaries.maxY;
                  transform.y = transBoundaries.minY;
              }
        }
        applyCss();
        _debouncedOverlay.call(self);
        _triggerUpdate.call(self);
    }

    function _getVirtualBoundaries(viewport) {
        var self = this,
            scale = self._currentZoom,
            vpWidth = viewport.width,
            vpHeight = viewport.height,
            centerFromBoundaryX = self.elements.boundary.clientWidth / 2,
            centerFromBoundaryY = self.elements.boundary.clientHeight / 2,
            imgRect = self.elements.preview.getBoundingClientRect(),
            curImgWidth = imgRect.width,
            curImgHeight = imgRect.height,
            halfWidth = vpWidth / 2,
            halfHeight = vpHeight / 2;

        var maxX = ((halfWidth / scale) - centerFromBoundaryX) * -1;
        var minX = maxX - ((curImgWidth * (1 / scale)) - (vpWidth * (1 / scale)));
        if( curImgWidth < vpWidth ) minX -= ((vpWidth - curImgWidth) / 2); 

        var maxY = ((halfHeight / scale) - centerFromBoundaryY) * -1;
        var minY = maxY - ((curImgHeight * (1 / scale)) - (vpHeight * (1 / scale)));
        if( curImgHeight < vpHeight ) minY -= ((vpHeight - curImgHeight) / 2); 

        var originMinX = (1 / scale) * halfWidth;
        var originMaxX = (curImgWidth * (1 / scale)) - originMinX;

        var originMinY = (1 / scale) * halfHeight;
        var originMaxY = (curImgHeight * (1 / scale)) - originMinY;

        return {
            translate: {
                maxX: maxX,
                minX: minX,
                maxY: maxY,
                minY: minY
            },
            origin: {
                maxX: originMaxX,
                minX: originMinX,
                maxY: originMaxY,
                minY: originMinY
            }
        };
    }

    function _updateCenterPoint(rotate) {
        var self = this,
            scale = self._currentZoom,
            data = self.elements.preview.getBoundingClientRect(),
            vpData = self.elements.viewport.getBoundingClientRect(),
            transform = Transform.parse(self.elements.preview.style[CSS_TRANSFORM]),
            pc = new TransformOrigin(self.elements.preview),
            top = (vpData.top - data.top) + (vpData.height / 2),
            left = (vpData.left - data.left) + (vpData.width / 2),
            center = {},
            adj = {};

        if (rotate) {
            var cx = pc.x;
            var cy = pc.y;
            var tx = transform.x;
            var ty = transform.y;

            center.y = cx;
            center.x = cy;
            transform.y = tx;
            transform.x = ty;
        }
        else {
            center.y = top / scale;
            center.x = left / scale;

            adj.y = (center.y - pc.y) * (1 - scale);
            adj.x = (center.x - pc.x) * (1 - scale);

            transform.x -= adj.x;
            transform.y -= adj.y;
        }

        var newCss = {};
        newCss[CSS_TRANS_ORG] = center.x + 'px ' + center.y + 'px';
        newCss[CSS_TRANSFORM] = transform.toString();
        css(self.elements.preview, newCss);
    }

    function _initDraggable() {
        var self = this,
            isDragging = false,
            originalX,
            originalY,
            originalDistance,
            vpRect,
            transform;

        function assignTransformCoordinates(deltaX, deltaY) {
            var imgRect = self.elements.preview.getBoundingClientRect(),
                top = transform.y + deltaY,
                left = transform.x + deltaX;

            if (self.options.enforceBoundary) {
                if (vpRect.top > imgRect.top + deltaY && vpRect.bottom < imgRect.bottom + deltaY) {
                    transform.y = top;
                }

                if (vpRect.left > imgRect.left + deltaX && vpRect.right < imgRect.right + deltaX) {
                    transform.x = left;
                }
            }
            else {
                transform.y = top;
                transform.x = left;
            }
        }

        function toggleGrabState(isDragging) {
          self.elements.preview.setAttribute('aria-grabbed', isDragging);
          self.elements.boundary.setAttribute('aria-dropeffect', isDragging? 'move': 'none');
        }

        function keyDown(ev) {
            var LEFT_ARROW  = 37,
                UP_ARROW    = 38,
                RIGHT_ARROW = 39,
                DOWN_ARROW  = 40;

            if (ev.shiftKey && (ev.keyCode === UP_ARROW || ev.keyCode === DOWN_ARROW)) {
                var zoom;
                if (ev.keyCode === UP_ARROW) {
                    zoom = parseFloat(self.elements.zoomer.value) + parseFloat(self.elements.zoomer.step)
                }
                else {
                    zoom = parseFloat(self.elements.zoomer.value) - parseFloat(self.elements.zoomer.step)
                }
                self.setZoom(zoom);
            }
            else if (self.options.enableKeyMovement && (ev.keyCode >= 37 && ev.keyCode <= 40)) {
                ev.preventDefault();
                var movement = parseKeyDown(ev.keyCode);

                transform = Transform.parse(self.elements.preview);
                document.body.style[CSS_USERSELECT] = 'none';
                vpRect = self.elements.viewport.getBoundingClientRect();
                keyMove(movement);
            }

            function parseKeyDown(key) {
                switch (key) {
                    case LEFT_ARROW:
                        return [1, 0];
                    case UP_ARROW:
                        return [0, 1];
                    case RIGHT_ARROW:
                        return [-1, 0];
                    case DOWN_ARROW:
                        return [0, -1];
                }
            }
        }

        function keyMove(movement) {
            var deltaX = movement[0],
                deltaY = movement[1],
                newCss = {};

            assignTransformCoordinates(deltaX, deltaY);

            newCss[CSS_TRANSFORM] = transform.toString();
            css(self.elements.preview, newCss);
            _updateOverlay.call(self);
            document.body.style[CSS_USERSELECT] = '';
            _updateCenterPoint.call(self);
            _triggerUpdate.call(self);
            originalDistance = 0;
        }

        function mouseDown(ev) {
            if (ev.button !== undefined && ev.button !== 0) return;

            ev.preventDefault();
            if (isDragging) return;
            isDragging = true;
            originalX = ev.pageX;
            originalY = ev.pageY;

            if (ev.touches) {
                var touches = ev.touches[0];
                originalX = touches.pageX;
                originalY = touches.pageY;
            }
            toggleGrabState(isDragging);
            transform = Transform.parse(self.elements.preview);
            window.addEventListener('mousemove', mouseMove);
            window.addEventListener('touchmove', mouseMove);
            window.addEventListener('mouseup', mouseUp);
            window.addEventListener('touchend', mouseUp);
            document.body.style[CSS_USERSELECT] = 'none';
            vpRect = self.elements.viewport.getBoundingClientRect();
        }

        function mouseMove(ev) {
            ev.preventDefault();
            var pageX = ev.pageX,
                pageY = ev.pageY;

            if (ev.touches) {
                var touches = ev.touches[0];
                pageX = touches.pageX;
                pageY = touches.pageY;
            }

            var deltaX = pageX - originalX,
                deltaY = pageY - originalY,
                newCss = {};

            if (ev.type === 'touchmove') {
                if (ev.touches.length > 1) {
                    var touch1 = ev.touches[0];
                    var touch2 = ev.touches[1];
                    var dist = Math.sqrt((touch1.pageX - touch2.pageX) * (touch1.pageX - touch2.pageX) + (touch1.pageY - touch2.pageY) * (touch1.pageY - touch2.pageY));

                    if (!originalDistance) {
                        originalDistance = dist / self._currentZoom;
                    }

                    var scale = dist / originalDistance;

                    _setZoomerVal.call(self, scale);
                    dispatchChange(self.elements.zoomer);
                    return;
                }
            }

            assignTransformCoordinates(deltaX, deltaY);

            newCss[CSS_TRANSFORM] = transform.toString();
            css(self.elements.preview, newCss);
            _updateOverlay.call(self);
            originalY = pageY;
            originalX = pageX;
        }

        function mouseUp() {
            isDragging = false;
            toggleGrabState(isDragging);
            window.removeEventListener('mousemove', mouseMove);
            window.removeEventListener('touchmove', mouseMove);
            window.removeEventListener('mouseup', mouseUp);
            window.removeEventListener('touchend', mouseUp);
            document.body.style[CSS_USERSELECT] = '';
            _updateCenterPoint.call(self);
            _triggerUpdate.call(self);
            originalDistance = 0;
        }

        self.elements.overlay.addEventListener('mousedown', mouseDown);
        self.elements.viewport.addEventListener('keydown', keyDown);
        self.elements.overlay.addEventListener('touchstart', mouseDown);
    }

    function _updateOverlay() {
        if (!this.elements) return; // since this is debounced, it can be fired after destroy
        var self = this,
            boundRect = self.elements.boundary.getBoundingClientRect(),
            imgData = self.elements.preview.getBoundingClientRect();

        css(self.elements.overlay, {
            width: imgData.width + 'px',
            height: imgData.height + 'px',
            top: (imgData.top - boundRect.top) + 'px',
            left: (imgData.left - boundRect.left) + 'px'
        });
    }
    var _debouncedOverlay = debounce(_updateOverlay, 500);

    function _triggerUpdate() {
        var self = this,
            data = self.get();

        if (!_isVisible.call(self)) {
            return;
        }

        self.options.update.call(self, data);
        if (self.$ && typeof Prototype === 'undefined') {
            self.$(self.element).trigger('update.croppie', data);
        }
        else {
            var ev;
            if (window.CustomEvent) {
                ev = new CustomEvent('update', { detail: data });
            } else {
                ev = document.createEvent('CustomEvent');
                ev.initCustomEvent('update', true, true, data);
            }

            self.element.dispatchEvent(ev);
        }
    }

    function _isVisible() {
        return this.elements.preview.offsetHeight > 0 && this.elements.preview.offsetWidth > 0;
    }

    function _updatePropertiesFromImage() {
        var self = this,
            initialZoom = 1,
            cssReset = {},
            img = self.elements.preview,
            imgData,
            transformReset = new Transform(0, 0, initialZoom),
            originReset = new TransformOrigin(),
            isVisible = _isVisible.call(self);

        if (!isVisible || self.data.bound) {// if the croppie isn't visible or it doesn't need binding
            return;
        }

        self.data.bound = true;
        cssReset[CSS_TRANSFORM] = transformReset.toString();
        cssReset[CSS_TRANS_ORG] = originReset.toString();
        cssReset['opacity'] = 1;
        css(img, cssReset);

        imgData = self.elements.preview.getBoundingClientRect();

        self._originalImageWidth = imgData.width;
        self._originalImageHeight = imgData.height;
        self.data.orientation = _hasExif.call(self) ? getExifOrientation(self.elements.img) : self.data.orientation;

        if (self.options.enableZoom) {
            _updateZoomLimits.call(self, true);
        }
        else {
            self._currentZoom = initialZoom;
        }

        transformReset.scale = self._currentZoom;
        cssReset[CSS_TRANSFORM] = transformReset.toString();
        css(img, cssReset);

        if (self.data.points.length) {
            _bindPoints.call(self, self.data.points);
        }
        else {
            _centerImage.call(self);
        }

        _updateCenterPoint.call(self);
        _updateOverlay.call(self);
    }

    function _updateZoomLimits (initial) {
        var self = this,
            minZoom = Math.max(self.options.minZoom, 0) || 0,
            maxZoom = self.options.maxZoom || 1.5,
            initialZoom,
            defaultInitialZoom,
            zoomer = self.elements.zoomer,
            scale = parseFloat(zoomer.value),
            boundaryData = self.elements.boundary.getBoundingClientRect(),
            imgData = naturalImageDimensions(self.elements.img, self.data.orientation),
            vpData = self.elements.viewport.getBoundingClientRect(),
//             img_ratio = ( imgData.width > imgData.height ) ? imgData.width / imgData.height : imgData.height / imgData.weight,
            minW,
            minH;
        if (self.options.enforceBoundary) {
          if( imgData.width > imgData.height ) {
            minW = vpData.width / imgData.width;
            minH = vpData.width / imgData.width;
//             minH = (vpData.height / img_ratio ) / imgData.height;
          } else {
            minH = vpData.height / imgData.height;
            minW = vpData.height / imgData.height;
          }
          minZoom = Math.max(minW, minH);
        }

        if (minZoom >= maxZoom) {
            maxZoom = minZoom + 1;
        }

        zoomer.min = fix(minZoom, 4);
        zoomer.max = fix(maxZoom, 4);

        if (!initial && (scale < zoomer.min || scale > zoomer.max)) {
            _setZoomerVal.call(self, scale < zoomer.min ? zoomer.min : zoomer.max);
        }
        else if (initial) {
            defaultInitialZoom = Math.max((boundaryData.width / imgData.width), (boundaryData.height / imgData.height));
            initialZoom = self.data.boundZoom !== null ? self.data.boundZoom : defaultInitialZoom;
            _setZoomerVal.call(self, initialZoom);
        }

        dispatchChange(zoomer);
    }

    function _bindPoints(points) {
        if (points.length !== 4) {
            throw "Croppie - Invalid number of points supplied: " + points;
        }
        var self = this,
            pointsWidth = points[2] - points[0],
            // pointsHeight = points[3] - points[1],
            vpData = self.elements.viewport.getBoundingClientRect(),
            boundRect = self.elements.boundary.getBoundingClientRect(),
            vpOffset = {
                left: vpData.left - boundRect.left,
                top: vpData.top - boundRect.top
            },
            scale = vpData.width / pointsWidth,
            originTop = points[1],
            originLeft = points[0],
            transformTop = (-1 * points[1]) + vpOffset.top,
            transformLeft = (-1 * points[0]) + vpOffset.left,
            newCss = {};

        newCss[CSS_TRANS_ORG] = originLeft + 'px ' + originTop + 'px';
        newCss[CSS_TRANSFORM] = new Transform(transformLeft, transformTop, scale).toString();
        css(self.elements.preview, newCss);

        _setZoomerVal.call(self, scale);
        self._currentZoom = scale;
    }

    function _centerImage() {
        var self = this,
            imgDim = self.elements.preview.getBoundingClientRect(),
            vpDim = self.elements.viewport.getBoundingClientRect(),
            boundDim = self.elements.boundary.getBoundingClientRect(),
            vpLeft = vpDim.left - boundDim.left,
            vpTop = vpDim.top - boundDim.top,
            w = vpLeft - ((imgDim.width - vpDim.width) / 2),
            h = vpTop - ((imgDim.height - vpDim.height) / 2),
            transform = new Transform(w, h, self._currentZoom);

        css(self.elements.preview, CSS_TRANSFORM, transform.toString());
    }

    function _transferImageToCanvas(customOrientation) {
        var self = this,
            canvas = self.elements.canvas,
            img = self.elements.img,
            ctx = canvas.getContext('2d');

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        canvas.width = img.width;
        canvas.height = img.height;

        var orientation = self.options.enableOrientation && customOrientation || getExifOrientation(img);
        drawCanvas(canvas, img, orientation);
    }

    function _getCanvas(data) {
        var self = this,
            points = data.points,
            left = num(points[0]),
            top = num(points[1]),
            right = num(points[2]),
            bottom = num(points[3]),
            width = right-left,
            height = bottom-top,
            circle = data.circle,
            canvas = document.createElement('canvas'),
            ctx = canvas.getContext('2d'),
            startX = 0,
            startY = 0,
            canvasWidth = data.outputWidth || width,
            canvasHeight = data.outputHeight || height;

        canvas.width = canvasWidth;
        canvas.height = canvasHeight;

        if (data.backgroundColor) {
            ctx.fillStyle = data.backgroundColor;
            ctx.fillRect(0, 0, canvasWidth, canvasHeight);
        }

        // By default assume we're going to draw the entire
        // source image onto the destination canvas.
        var sx = left,
            sy = top,
            sWidth = width,
            sHeight = height,
            dx = 0,
            dy = 0,
            dWidth = canvasWidth,
            dHeight = canvasHeight;

        //
        // Do not go outside of the original image's bounds along the x-axis.
        // Handle translations when projecting onto the destination canvas.
        //

        // The smallest possible source x-position is 0.
        if (left < 0) {
            sx = 0;
            dx = (Math.abs(left) / width) * canvasWidth;
        }

        // The largest possible source width is the original image's width.
        if (sWidth + sx > self._originalImageWidth) {
            sWidth = self._originalImageWidth - sx;
            dWidth =  (sWidth / width) * canvasWidth;
        }

        //
        // Do not go outside of the original image's bounds along the y-axis.
        //

        // The smallest possible source y-position is 0.
        if (top < 0) {
            sy = 0;
            dy = (Math.abs(top) / height) * canvasHeight;
        }

        // The largest possible source height is the original image's height.
        if (sHeight + sy > self._originalImageHeight) {
            sHeight = self._originalImageHeight - sy;
            dHeight = (sHeight / height) * canvasHeight;
        }

        // console.table({ left, right, top, bottom, canvasWidth, canvasHeight, width, height, startX, startY, circle, sx, sy, dx, dy, sWidth, sHeight, dWidth, dHeight });

        ctx.drawImage(this.elements.preview, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight);
        if (circle) {
            ctx.fillStyle = '#fff';
            ctx.globalCompositeOperation = 'destination-in';
            ctx.beginPath();
            ctx.arc(canvas.width / 2, canvas.height / 2, canvas.width / 2, 0, Math.PI * 2, true);
            ctx.closePath();
            ctx.fill();
        }
        return canvas;
    }

    function _getHtmlResult(data) {
        var points = data.points,
            div = document.createElement('div'),
            img = document.createElement('img'),
            width = points[2] - points[0],
            height = points[3] - points[1];

        addClass(div, 'croppie-result');
        div.appendChild(img);
        css(img, {
            left: (-1 * points[0]) + 'px',
            top: (-1 * points[1]) + 'px'
        });
        img.src = data.url;
        css(div, {
            width: width + 'px',
            height: height + 'px'
        });

        return div;
    }

    function _getBase64Result(data) {
        return _getCanvas.call(this, data).toDataURL(data.format, data.quality);
    }

    function _getBlobResult(data) {
        var self = this;
        return new Promise(function (resolve) {
            _getCanvas.call(self, data).toBlob(function (blob) {
                resolve(blob);
            }, data.format, data.quality);
        });
    }

    function _replaceImage(img) {
        if (this.elements.img.parentNode) {
            Array.prototype.forEach.call(this.elements.img.classList, function(c) { img.classList.add(c); });
            this.elements.img.parentNode.replaceChild(img, this.elements.img);
            this.elements.preview = img; // if the img is attached to the DOM, they're not using the canvas
        }
        this.elements.img = img;
    }

    function _bind(options, cb) {
        var self = this,
            url,
            points = [],
            zoom = null,
            hasExif = _hasExif.call(self);

        if (typeof (options) === 'string') {
            url = options;
            options = {};
        }
        else if (Array.isArray(options)) {
            points = options.slice();
        }
        else if (typeof (options) === 'undefined' && self.data.url) { //refreshing
            _updatePropertiesFromImage.call(self);
            _triggerUpdate.call(self);
            return null;
        }
        else {
            url = options.url;
            points = options.points || [];
            zoom = typeof(options.zoom) === 'undefined' ? null : options.zoom;
        }

        self.data.bound = false;
        self.data.url = url || self.data.url;
        self.data.boundZoom = zoom;

        return loadImage(url, hasExif).then(function (img) {
            _replaceImage.call(self, img);
            if (!points.length) {
                var natDim = naturalImageDimensions(img);
                var rect = self.elements.viewport.getBoundingClientRect();
                var aspectRatio = rect.width / rect.height;
                var imgAspectRatio = natDim.width / natDim.height;
                var width, height;

                if (imgAspectRatio > aspectRatio) {
                    height = natDim.height;
                    width = height * aspectRatio;
                }
                else {
                    width = natDim.width;
                    height = natDim.height / aspectRatio;
                }

                var x0 = (natDim.width - width) / 2;
                var y0 = (natDim.height - height) / 2;
                var x1 = x0 + width;
                var y1 = y0 + height;
                self.data.points = [x0, y0, x1, y1];
            }
            else if (self.options.relative) {
                points = [
                    points[0] * img.naturalWidth / 100,
                    points[1] * img.naturalHeight / 100,
                    points[2] * img.naturalWidth / 100,
                    points[3] * img.naturalHeight / 100
                ];
            }

            self.data.orientation = options.orientation || 1;
            self.data.points = points.map(function (p) {
                return parseFloat(p);
            });
            if (self.options.useCanvas) {
                _transferImageToCanvas.call(self, self.data.orientation);
            }
            _updatePropertiesFromImage.call(self);
            _triggerUpdate.call(self);
            cb && cb();
        });
    }

    function fix(v, decimalPoints) {
        return parseFloat(v).toFixed(decimalPoints || 0);
    }

    function _get() {
        var self = this,
            imgData = self.elements.preview.getBoundingClientRect(),
            vpData = self.elements.viewport.getBoundingClientRect(),
            x1 = vpData.left - imgData.left,
            y1 = vpData.top - imgData.top,
            widthDiff = (vpData.width - self.elements.viewport.offsetWidth) / 2, //border
            heightDiff = (vpData.height - self.elements.viewport.offsetHeight) / 2,
            x2 = x1 + self.elements.viewport.offsetWidth + widthDiff,
            y2 = y1 + self.elements.viewport.offsetHeight + heightDiff,
            scale = self._currentZoom;

        if (scale === Infinity || isNaN(scale)) {
            scale = 1;
        }

        var max = self.options.enforceBoundary ? 0 : Number.NEGATIVE_INFINITY;
        x1 = Math.max(max, x1 / scale);
        y1 = Math.max(max, y1 / scale);
        x2 = Math.max(max, x2 / scale);
        y2 = Math.max(max, y2 / scale);

        return {
            points: [fix(x1), fix(y1), fix(x2), fix(y2)],
            zoom: scale,
            orientation: self.data.orientation
        };
    }

    var RESULT_DEFAULTS = {
            type: 'canvas',
            format: 'png',
            quality: 1
        },
        RESULT_FORMATS = ['jpeg', 'webp', 'png'];

    function _result(options) {
        var self = this,
            data = _get.call(self),
            opts = deepExtend(clone(RESULT_DEFAULTS), clone(options)),
            resultType = (typeof (options) === 'string' ? options : (opts.type || 'base64')),
            size = opts.size || 'viewport',
            format = opts.format,
            quality = opts.quality,
            backgroundColor = opts.backgroundColor,
            circle = typeof opts.circle === 'boolean' ? opts.circle : (self.options.viewport.type === 'circle'),
            vpRect = self.elements.viewport.getBoundingClientRect(),
            ratio = vpRect.width / vpRect.height,
            prom;

        if (size === 'viewport') {
            data.outputWidth = vpRect.width;
            data.outputHeight = vpRect.height;
        } else if (typeof size === 'object') {
            if (size.width && size.height) {
                data.outputWidth = size.width;
                data.outputHeight = size.height;
            } else if (size.width) {
                data.outputWidth = size.width;
                data.outputHeight = size.width / ratio;
            } else if (size.height) {
                data.outputWidth = size.height * ratio;
                data.outputHeight = size.height;
            }
        }

        if (RESULT_FORMATS.indexOf(format) > -1) {
            data.format = 'image/' + format;
            data.quality = quality;
        }

        data.circle = circle;
        data.url = self.data.url;
        data.backgroundColor = backgroundColor;

        prom = new Promise(function (resolve) {
            switch(resultType.toLowerCase())
            {
                case 'rawcanvas':
                    resolve(_getCanvas.call(self, data));
                    break;
                case 'canvas':
                case 'base64':
                    resolve(_getBase64Result.call(self, data));
                    break;
                case 'blob':
                    _getBlobResult.call(self, data).then(resolve);
                    break;
                default:
                    resolve(_getHtmlResult.call(self, data));
                    break;
            }
        });
        return prom;
    }

    function _refresh() {
        _updatePropertiesFromImage.call(this);
    }

    function _rotate(deg) {
        if (!this.options.useCanvas || !this.options.enableOrientation) {
            throw 'Croppie: Cannot rotate without enableOrientation && EXIF.js included';
        }

        var self = this,
            canvas = self.elements.canvas;

        self.data.orientation = getExifOffset(self.data.orientation, deg);
        drawCanvas(canvas, self.elements.img, self.data.orientation);
        _updateCenterPoint.call(self, true);
        _updateZoomLimits.call(self);

        // Reverses image dimensions if the degrees of rotation is not divisible by 180.
        if ((Math.abs(deg) / 90) % 2 === 1) {
            var oldHeight = self._originalImageHeight;
            var oldWidth = self._originalImageWidth;
            self._originalImageWidth = oldHeight;
            self._originalImageHeight = oldWidth;
        }
    }

    function _destroy() {
        var self = this;
        self.element.removeChild(self.elements.boundary);
        removeClass(self.element, 'croppie-container');
        if (self.options.enableZoom) {
            self.element.removeChild(self.elements.zoomerWrap);
        }
        delete self.elements;
    }

    if (typeof window !== 'undefined' && window.jQuery) {
        var $ = window.jQuery;
        $.fn.croppie = function (opts) {
            var ot = typeof opts;

            if (ot === 'string') {
                var args = Array.prototype.slice.call(arguments, 1);
                var singleInst = $(this).data('croppie');

                if (opts === 'get') {
                    return singleInst.get();
                }
                else if (opts === 'result') {
                    return singleInst.result.apply(singleInst, args);
                }
                else if (opts === 'bind') {
                    return singleInst.bind.apply(singleInst, args);
                }

                return this.each(function () {
                    var i = $(this).data('croppie');
                    if (!i) return;

                    var method = i[opts];
                    if ($.isFunction(method)) {
                        method.apply(i, args);
                        if (opts === 'destroy') {
                            $(this).removeData('croppie');
                        }
                    }
                    else {
                        throw 'Croppie ' + opts + ' method not found';
                    }
                });
            }
            else {
                return this.each(function () {
                    var i = new Croppie(this, opts);
                    i.$ = $;
                    $(this).data('croppie', i);
                });
            }
        };
    }

    function Croppie(element, opts) {
        if (element.className.indexOf('croppie-container') > -1) {
            throw new Error("Croppie: Can't initialize croppie more than once");
        }
        this.element = element;
        this.options = deepExtend(clone(Croppie.defaults), opts);

        if (this.element.tagName.toLowerCase() === 'img') {
            var origImage = this.element;
            addClass(origImage, 'cr-original-image');
            setAttributes(origImage, {'aria-hidden' : 'true', 'alt' : '' });
            var replacementDiv = document.createElement('div');
            this.element.parentNode.appendChild(replacementDiv);
            replacementDiv.appendChild(origImage);
            this.element = replacementDiv;
            this.options.url = this.options.url || origImage.src;
        }

        _create.call(this);
        if (this.options.url) {
            var bindOpts = {
                url: this.options.url,
                points: this.options.points
            };
            delete this.options['url'];
            delete this.options['points'];
            _bind.call(this, bindOpts);
        }
    }

    Croppie.defaults = {
        viewport: {
            width: 100,
            height: 100,
            type: 'square'
        },
        boundary: { },
        orientationControls: {
            enabled: true,
            leftClass: '',
            rightClass: ''
        },
        resizeControls: {
            width: true,
            height: true
        },
        customClass: '',
        showZoomer: true,
        enableZoom: true,
        enableResize: false,
        mouseWheelZoom: true,
        enableExif: false,
        enforceBoundary: true,
        enableOrientation: false,
        enableKeyMovement: true,
        update: function () { }
    };

    Croppie.globals = {
        translate: 'translate3d'
    };

    deepExtend(Croppie.prototype, {
        bind: function (options, cb) {
            return _bind.call(this, options, cb);
        },
        get: function () {
            var data = _get.call(this);
            var points = data.points;
            if (this.options.relative) {
                points[0] /= this.elements.img.naturalWidth / 100;
                points[1] /= this.elements.img.naturalHeight / 100;
                points[2] /= this.elements.img.naturalWidth / 100;
                points[3] /= this.elements.img.naturalHeight / 100;
            }
            return data;
        },
        result: function (type) {
            return _result.call(this, type);
        },
        refresh: function () {
            return _refresh.call(this);
        },
        setZoom: function (v) {
            _setZoomerVal.call(this, v);
            dispatchChange(this.elements.zoomer);
        },
        rotate: function (deg) {
            _rotate.call(this, deg);
        },
        destroy: function () {
            return _destroy.call(this);
        }
    });
    return Croppie;
}));
/*!
 * JavaScript Cookie v2.2.1
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
;(function (factory) {
	var registeredInModuleLoader;
	if (typeof define === 'function' && define.amd) {
		define(factory);
		registeredInModuleLoader = true;
	}
	if (typeof exports === 'object') {
		module.exports = factory();
		registeredInModuleLoader = true;
	}
	if (!registeredInModuleLoader) {
		var OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function decode (s) {
		return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
	}

	function init (converter) {
		function api() {}

		function set (key, value, attributes) {
			if (typeof document === 'undefined') {
				return;
			}

			attributes = extend({
				path: '/'
			}, api.defaults, attributes);

			if (typeof attributes.expires === 'number') {
				attributes.expires = new Date(new Date() * 1 + attributes.expires * 864e+5);
			}

			// We're using "expires" because "max-age" is not supported by IE
			attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

			try {
				var result = JSON.stringify(value);
				if (/^[\{\[]/.test(result)) {
					value = result;
				}
			} catch (e) {}

			value = converter.write ?
				converter.write(value, key) :
				encodeURIComponent(String(value))
					.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);

			key = encodeURIComponent(String(key))
				.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
				.replace(/[\(\)]/g, escape);

			var stringifiedAttributes = '';
			for (var attributeName in attributes) {
				if (!attributes[attributeName]) {
					continue;
				}
				stringifiedAttributes += '; ' + attributeName;
				if (attributes[attributeName] === true) {
					continue;
				}

				// Considers RFC 6265 section 5.2:
				// ...
				// 3.  If the remaining unparsed-attributes contains a %x3B (";")
				//     character:
				// Consume the characters of the unparsed-attributes up to,
				// not including, the first %x3B (";") character.
				// ...
				stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
			}

			return (document.cookie = key + '=' + value + stringifiedAttributes);
		}

		function get (key, json) {
			if (typeof document === 'undefined') {
				return;
			}

			var jar = {};
			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all.
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var cookie = parts.slice(1).join('=');

				if (!json && cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					var name = decode(parts[0]);
					cookie = (converter.read || converter)(cookie, name) ||
						decode(cookie);

					if (json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					jar[name] = cookie;

					if (key === name) {
						break;
					}
				} catch (e) {}
			}

			return key ? jar[key] : jar;
		}

		api.set = set;
		api.get = function (key) {
			return get(key, false /* read as raw */);
		};
		api.getJSON = function (key) {
			return get(key, true /* read as json */);
		};
		api.remove = function (key, attributes) {
			set(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.defaults = {};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));

//var for all data related to the form
var g365_form_data = {};
// if( typeof g365_form_details === 'undefined'  ) var g365_form_details = {items: {}};
var g365_start_status = false;
//placeholder to add functionality after basic form is loaded.
function g365_extend_form (target_container){ /*to prevent error when there is no extension*/ }

function g365_form_start_up( target_container ) {
  //select options with key
  $('select[data-g365_select]', target_container).each(function(){var select_ele = $(this); $('option[value="' + select_ele.attr('data-g365_select') + '"]', select_ele).prop('selected', true);});
  $('.crop_img', target_container).each(function(){ g365_start_croppie( $(this) ); });
  $('.grade-graduation', target_container).change(function(){ g365_grad_year_controller( $(this) ); }).change();
  $('[data-g365_contingent]', target_container).change(function(){ g365_contingent_manager( $(this) ); }).change();
  $('[data-g365_additional_target_limit]', target_container).each(function(){
    var this_target = $(this);
    this_target.change(function(){ g365_addition_limiter( this_target ); }).change();
//     $('#' + this_target.attr('data-g365_additional_target_limit')).change( function(){ this_target.change(); });
  });
  $('.select_loader', target_container).on('change', function(){ g365_build_dropdown_from_data( $(this) ); }).change();
  $('.select_local', target_container).on('change', function(e){ g365_build_dropdown_from_object( $(this) ); }).change();
  $('.select_calc', target_container).on('change', function(){ g365_set_field_calc( $(this) ); }).change();
  $('.form_loader', target_container).on('click keyup', function(e){
    e.preventDefault();
    var keycode = e.keyCode || e.which;
    if (e.type === 'keyup' && keycode !== 13) return;
    g365_build_form_from_data( $(this) );
  });
  $('.field-toggle', target_container).click(function(){ g365_field_toggle( $(this) ); });
  $(".site-close-button", target_container).click( g365_form_section_closer );
  $(".g365-input-formatter", target_container).on('change keyup input', function(){ g365_field_format_lock( $(this), $(this).attr('data-g365_input_format') ) });
  $(".g365-expand-collapse-fieldset", target_container).on('click', function(e, direction){ if( $(e.target).is('input') || $(e.target).is('label') || $(e.target).is('#viewProfileBtn')) return; g365_form_section_expand_collapse( $(this), direction ); });
  $('.change-title', target_container).each(function(){ g365_change_title( $(this) ) });
  
  window.g365_func_wrapper.end[window.g365_func_wrapper.end.length] = {name : g365_extend_form, args : [target_container]};

  g365_livesearch_init( target_container, ((target_container.attr('id') !== 'g365_form_wrap') ? target_container.attr('id') : null) );
  var form_child = target_container.children('.primary-form');
  if( form_child.length ) form_child.submit( g365_handle_form_submit );
  //foundation dependancies
  target_container.foundation();
}

function g365_change_title( target ){
  if( target instanceof jQuery ) {
    var ele_targets = target.attr('data-g365_change_targets');
    var display_totals = target.attr('data_g365_change_totals');
    var delimiter = target.attr('data-g365_change_delimiter');
    delimiter = (typeof delimiter === 'undefined') ? ' ' : delimiter;
    if( typeof ele_targets === 'undefined' || target === '' ) return;
    ele_targets = $(ele_targets.split('|').join());
    if( ele_targets.length === 0 ) return;
    ele_targets.on('keyup change', function(){
      var full_name = [];
      ele_targets.each(function(){
        var new_target = $(this);
        if( new_target.is('select') ) {
          var target_option = $('option:selected', new_target);
          if(target_option.val() !== '' && target_option.val() !== 'new') full_name.push( (typeof target_option.attr('data-g365_short_name') === 'undefined') ? target_option.text() : target_option.attr('data-g365_short_name') );
        } else {
          if(new_target.val() !== '' && new_target.val() !== 'new') full_name.push( (typeof new_target.attr('data-g365_short_name') === 'undefined') ? new_target.val() : new_target.attr('data-g365_short_name') );
        }
      });
      full_name = full_name.filter(function (el) { return el != ''; }).join(delimiter);
      if( display_totals === 'true' && ele_targets.first().is('select') ) {
        full_name += ' <span class="count_totals">(' + (ele_targets.first().children('option:disabled:not([value=""])').length + 1) + ' of ' + ele_targets.first().children('option:not([value=""])').length + ')</span>';
      }
      if( target.is( 'input' ) ) {
        target.val((full_name === '') ? target.attr('data-default_value') : full_name);
      } else {
        target.html((full_name === '') ? target.attr('data-default_value') : full_name);
      }
    }).trigger('keyup');
  }
}
//functionality for the user roster admin multi-add button
function g365_bulk_add(e) {
  e.preventDefault();
  var click_ele = $(this);
  var click_href = click_ele.attr('href');
  if( click_href == '' || typeof click_href === 'undefined' ) return;
  var bulk_control = $('#' + click_ele.attr('data-g365_bulk_add_control'));
  var bulk_items = $('#' + click_ele.attr('data-g365_bulk_add_target'));
  //if we have a control element
  if( bulk_control.length === 1 ) {
    var bulk_control_val = bulk_control.val();
    if( bulk_control_val == '' || typeof bulk_control_val === 'undefined' ) {
      if( typeof bulk_control.attr('data-g365_bulk_add_default') !== 'undefined' ) {
        bulk_control_val = bulk_control.attr('data-g365_bulk_add_default');
      } else {
        console.log('bulk fail');
        return;
      }
    }
    //add to the url
    click_href += bulk_control_val;
    //see if we have team ids to auto-load and it's not the default club team, it has to be added independently
    if( bulk_items.length === 1 && bulk_control_val != 0 ) {
      var bulk_items_select = $('.bulk-add-checkbox input:checked', bulk_items);
      var bulk_items_vals = '';
      if( bulk_items_select.length > 0 ) {
        bulk_items_vals = [];
        bulk_items_select.each(function(){
          bulk_items_vals.push( $(this).attr('data-g365_bulk_id') );
        });
      }
      if( typeof bulk_items_vals === 'object' ) click_href += '?ro_ids=' + bulk_items_vals.join();
    }
  }
  //navigate once modification is done.
  window.location.href = click_href;
}
//       var full_name = '';
//       ele_targets.each(function(){
//         var new_target = $(this);
//         full_name += ' ';
//         if( new_target.is('select') ) {
//           var target_option = $('option:selected', new_target);
//           full_name += (typeof target_option.attr('data-g365_short_name') === 'undefined') ? ((target_option.val() === '' || target_option.val() === 'new') ? '' : ((full_name !== ' ' && delimiter) ? delimiter : '') + target_option.text()) : ((full_name !== ' ' && delimiter) ? delimiter : '') + target_option.attr('data-g365_short_name');
//         } else {
//           full_name += (typeof new_target.attr('data-g365_short_name') === 'undefined') ? ((new_target.val() === '' || new_target.val() === 'null') ? '' : ((full_name !== ' ' && delimiter) ? delimiter : '') + new_target.val()) : ((full_name !== ' ' && delimiter) ? delimiter : '') + new_target.attr('data-g365_short_name');
//         }
//       });
//       full_name = full_name.trim();
//       if( full_name.startsWith('| ') ) full_name = full_name.substring(2);
//       if( full_name.endsWith(' |') ) full_name = full_name.substring(0,-3);
//       if( display_totals === 'true' && ele_targets.first().is('select') ) {
//         full_name += ' <span class="count_totals">(' + (ele_targets.first().children('option:disabled:not([value=""])').length + 1) + ' of ' + ele_targets.first().children('option:not([value=""])').length + ')</span>';
//       }
//       if( target.is( 'input' ) ) {
//         target.val((full_name === '') ? target.attr('data-default_value') : full_name);
//       } else {
//         target.html((full_name === '') ? target.attr('data-default_value') : full_name);
//       }



//add smooth scoll to the form closers
$.fn.scrollView = function () {
  var html_body = $('html, body');
  var win_height = $(window).height();
  var scroll_target = '';
  if( html_body.data( 'force_next_scroll' ) === true ) {
    scroll_target = html_body.data( 'next_scroll_target' );
    html_body.removeData( 'force_next_scroll' );
  } else {
    scroll_target = ($(this).offset().top - (win_height/4));
  }
  //if the target is too close (in the middle of the current veiw) don't scroll
  if( Math.abs(scroll_target - html_body.scrollTop()) < (win_height/2)) return false;
  //set the target to check against if we are allowed to scroll
  html_body.data( 'next_scroll_target', scroll_target );
  //if we are already in a scroll, then exit
  if( html_body.data( 'scroll_in_progress' ) === true ) return false;
  //if it's allowed then set us in scroll mode
  html_body.data( 'scroll_in_progress', true );
  return this.each(function () {
    html_body.animate({
      scrollTop: scroll_target
    }, 500, function(){
      if( scroll_target == html_body.data( 'next_scroll_target' ) ) {
        html_body.data( 'scroll_in_progress', false );
        html_body.removeData( 'next_scroll_target' );
      } else {
        html_body.data( 'force_next_scroll', true );
        $(this).scrollView();
      }
    });
  });
}
//toggles next element, takes options, old
// function g365_field_toggle( toggle_button ) {
//   var field_title = $('.field-title', toggle_button);
//   var field_button = $('.field-button', toggle_button);
// //   <a class="field-toggle block text-right" data-g365_target="event-selector" data-g365_after="hide">select event</a>
//   //if the button has a target, use it, else toggle the next element
//   if( typeof toggle_button.attr('data-g365_target') !== 'undefined' ) {
//     $('#' + toggle_button.attr('data-g365_target')).slideToggle();
//   } else {
//     toggle_button.next().slideToggle();
//   }
//   //if the button should disappear after use, do that.
//   if( toggle_button.attr('data-g365_after') === 'hide' ) toggle_button.hide();
//   var field_toggle_name = field_button.html().match(/^(.+?) (.+)$/);
//   var data_compile = {};
//   var data_capture = [];
//   if( field_toggle_name !== false && Array.isArray(field_toggle_name) ) {
//     var field_action_title = '';
//     //what state are we in
//     data_capture = toggle_button.attr('data-data_capture').split('|');
//     if( field_toggle_name[1] === 'select' || field_toggle_name[1] === 'change' || field_toggle_name[1] === 'add' ) {
//       //when they clicked, it said select
//       if( typeof toggle_button.attr('data-g365_change_title') === 'undefined' ) {
//         toggle_button.attr('data-g365_change_title', field_toggle_name[1]);
//         field_action_title = field_toggle_name[1];
//         toggle_button.addClass('locked-in');
//       } else {
//         field_action_title = 'revert';
//         toggle_button.removeClass('locked-in');
//         field_title.hide();
//       }
//       if( data_capture[0] !== 'undefined' ) {
//         $.each(data_capture, function(dex, target_id){
//           data_compile[target_id] = $('#' + target_id).val();
//         });
//         toggle_button.data('og_vals', data_compile);
//       }
//     } else {
//       //when they clicked, it said revert
//       field_action_title = toggle_button.attr('data-g365_change_title');
//       toggle_button.addClass('locked-in');
//       field_title.show();
//       var revert_targets = toggle_button.data( 'og_vals' );
//       if( typeof revert_targets !== 'undefined' ) {
//         $.each(revert_targets, function(id_key, og_val){
//           $('#' + id_key).val(og_val).change();
//         });
//         data_compile = revert_targets;
//       }
//     }
//     field_title.html( ( typeof data_compile[data_capture[0]] === 'undefined' || data_compile[data_capture[0]] === '' ) ? ( (typeof field_toggle_name[2] === 'undefined') ? 'Datapoint not set.' : (field_toggle_name[2] + ' not set') ) : data_compile[data_capture[0]] );
//     field_button.html( field_action_title + ( (typeof field_toggle_name[2] === 'undefined') ? '' : (' ' + field_toggle_name[2]) ) );
//   }
// }

//toggles next element, takes options
function g365_field_toggle( toggle_button ) {
  if( typeof toggle_button.attr('data-g365_target') !== 'undefined' ) {
    $('#' + toggle_button.attr('data-g365_target')).slideToggle();
  } else {
    if( typeof toggle_button.attr('data-g365_class_toggle') !== 'undefined' ) {
      toggle_button.next().toggleClass( toggle_button.attr('data-g365_class_toggle') );
      toggle_button.addClass('hide');
    } else {
      toggle_button.next().slideToggle();
    }
  }
  var field_button = $('.field-button', toggle_button);
  if( field_button.length === 1 && typeof toggle_button.attr('data-g365_before') !== 'undefined' && typeof toggle_button.attr('data-g365_after') !== 'undefined' ) {
    if( toggle_button.attr('data-g365_before') == field_button.html() ) {
      field_button.html( toggle_button.attr('data-g365_after') );
    } else {
      field_button.html( toggle_button.attr('data-g365_before') );
    }
  }
}

//add functionality to collapse and re-expand field section
function g365_form_section_expand_collapse( caller, direction ) {
  var target_section_string = caller.attr('data-click-target');
  var target_section_title = $('#' + target_section_string + '_fieldset_title');
  var target_section = $('#' + target_section_string + '_fieldset');
  //check for valid before closing..
  if(g365_check_validation(target_section)) return false;
  //update section name
  var title_compile = '';
  $('.change-title', target_section).each(function(){
    var change_ele = $(this);
    var change_default = change_ele.attr('data-default_value');
    title_compile += ((typeof change_default === 'undefined' || change_default === '' &&  change_default !== 'none') ? '<small class="block">' + change_ele.html() + '</small>' : change_ele.html() + ' ');
  });
  $('span', target_section_title).first().html( title_compile );
  //if we have a direction (open, close)
  if( typeof direction !== 'undefined' ){
    if( direction === 'open' && target_section.hasClass('form-disabled') === false ) {
      target_section.removeClass('hide');
      target_section_title.addClass('hide');
    } else {
      target_section.addClass('hide');
      target_section_title.removeClass('hide');
    }
  } else {
    if( target_section.hasClass('hide') && target_section.hasClass('form-disabled') ) return false;
    //make sure the top of the expand section is visible
//     target_section.scrollView();
    //toggle field section
    target_section.toggleClass('hide');
    //toggle the section title
    target_section_title.toggleClass('hide');
  }
  return true;
}

//see if we have any invalid elements
function g365_check_validation(target) {
  var all_inputs = $("input, select, textarea", target);
  var errs = false;
  all_inputs.each(function(){
    var ele = $(this);
    if(ele[0].checkValidity()) {
      ele.parent().removeClass('error_item');
    } else {
      ele.parent().addClass('error_item');
      ele.on('focusout', function(){
        if(ele[0].checkValidity()) {
          ele.parent().removeClass('error_item');
          ele.off('focusout');
        }
      });
      errs = true;
    }
  });
  
  return errs;
}

function g365_form_section_closer() {
  //grab a reference to manage master submit button
  var field_wrapper = $(this).parent().parent().parent().parent();
  //to remove element
  var to_remove = $(this).parent().parent().parent();
  //origin info
  var to_remove_dropdown_key = to_remove.attr('data-g365_dropdown_key');
  var to_remove_dropdown_target = to_remove.attr('data-g365_dropdown_target');
  to_remove_dropdown_target = $('#' + to_remove_dropdown_target);

  if( to_remove_dropdown_target.length === 1 ) {
    //make sure that this is visible in case it got folded up
    var to_remove_dropdown_target_closest = to_remove_dropdown_target.closest('.form-holder').children(':not(.primary-form)').removeClass('hide').parent();
    to_remove_dropdown_target_closest.parent().children(':not(.form-holder)').removeClass('hide');
  }
  //if there aren't any other entries open any init forms which may have been hidden and hide their respective toggle buttons
  if( to_remove.siblings().length < 1 ) to_remove.closest('.form-holder').children(':not(.primary-form)').removeClass('hide').prev('.field-toggle').addClass('hide');
  //if there is a reference to this element in the order_data field, strip it
  var order_data_field = $('#g365_order_data');
  var order_data_field_val = ( order_data_field.length === 1 ) ? order_data_field.val() : '';
  if( order_data_field_val !== null || order_data_field_val !== 'null' || order_data_field_val !== '' ) order_data_field.val('null');

  //remove section field set
  to_remove.slideUp( "normal", function() {
    $(this).remove();
    //if there are no more sections, hide the master submit button (for master forms)
    if( field_wrapper.children('div').length === 0 ) field_wrapper.next('.g365_form_sub_block').hide();
    //if we have origin dropdown info, reactive, and select the new option
    if( typeof to_remove_dropdown_key !== 'undefined' && typeof to_remove_dropdown_target !== 'undefined' && to_remove_dropdown_target.length === 1 ) {
      to_remove_dropdown_target.children('option').map( function() {
        if($(this).text() == to_remove_dropdown_key) {
          $(this).prop('disabled', false).prop('selected', true);
          to_remove_dropdown_target.change();
        }
      });
    }
  });
  //if the previous search input form is hidden, reactivate it (for nested select forms only)
  if( field_wrapper.prev().is(':hidden') ) field_wrapper.prev().slideDown();
  if( field_wrapper.prev().is('select') ) field_wrapper.prev().val('');

  //Makes "Create Player" add button re-appear
  if($('#addBtnProfile')) {
     $('#addBtnProfile').show();
    }

}

function g365_form_message_clear( form ) {
  function g365_clear_message( event ) {
    form = event.data.wrapper_form;
    $('#' + form.attr('id') + '_message').html('').addClass('hide');
    form.off( 'focus', ':input', g365_clear_message);
  }
  form.on( 'focus', ':input', { wrapper_form: form }, g365_clear_message );
}

function g365_grad_year_controller( target_element ){
  var grad_target = $( '#' + target_element.attr('id') + '_grad_yr' );
  if( grad_target.length === 1 ) {
    var grade_val = $('option:selected', target_element).val();
    if( grade_val === '' ) {
      grad_target.val( '' );
    } else {
      var month = new Date();
      var year = month.getFullYear();
      month = month.getMonth();
      grad_year = year + (( month < 8 ) ? 12 : 13 ) - grade_val;
      grad_target.val( grad_year );
    }
  }
}

function g365_start_croppie( target_element ) {
	var $uploadCrop;
	function readFile(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$uploadCrop.parent().addClass('crop-size').removeClass('hide');
				$uploadCrop.croppie('bind', {
					url: e.target.result
				}).then(function(){
				});
			}
			reader.readAsDataURL(input.files[0]);
		} else {
			alert("Sorry - you're browser doesn't support the FileReader API.");
      input.parentNode.parentNode.innerHTML = "<p>Sorry - you're browser doesn't support the FileReader API</p>";
		}
	}

  
  
  var image_sizes = target_element.attr('data-g365_crop_settings'),
    viewport = {width: 400, height: 300},
    size = { width: 420, height: 320 },
    result_size =  { width: 400, height: 300 },
    enforceBoundaryMin = true,
    enforceBoundary = true,
    backgroundColor = false,
    format = 'jpg';
  switch(image_sizes) {
    case 'profile':
      viewport = {width: 200, height: 300, type: 'square'}; 
      size = { width: 250, height: 350 };
      result_size = { width: 400, height: 600 };
      break;
    case 'reportcard':
      viewport = {width: 300, height: 300, type: 'square'}; 
      size = { width: '100%', height: 320 };
      result_size = { width: 2000, height: 2000 };
//       enforceBoundaryMin = true;
      backgroundColor = '#000';
      break;
    case 'birthcert':
      console.log("welp3");
      viewport = {width: 300, height: 300, type: 'square'}; 
      size = { width: 420, height: 320 };
      result_size = { width: 2000, height: 2000 };
      backgroundColor = '#000';
      break;
    case 'org_profile':
      viewport = {width: 300, height: 225, type: 'square'}; 
      size = { width: 400, height: 300 };
      result_size = { width: 400, height: 300 };
      format = 'png';
      break;
    default:
      image_sizes = (typeof image_sizes === 'string' && image_sizes !== '') ? image_sizes.split('|') : '';
      if( Array.isArray( image_sizes ) ) {
        image_sizes.forEach(function(image_size, dex){
          image_sizes[dex] = image_sizes[dex].split(',');
          if( !image_sizes[dex].isArray() || !Number.isInteger(image_sizes[dex][0]) || !Number.isInteger(image_sizes[dex][1]) ) {
            image_sizes = '';
            return false;
          }
        });
        viewport = {width: image_sizes[0][0], height: image_sizes[0][1]};
        size = { width: image_sizes[1][0], height: image_sizes[1][1] };
      }
      break;
  }
  var desiredMaximumWidth = viewport.width; // pixels
	$uploadCrop = $('.crop_upload_canvas', target_element).croppie({
    boundary: size,
    viewport: viewport,
		enableExif: true,
    enableOrientation: true,
		mouseWheelZoom: false,
    enforceBoundary: true
	});
	$uploadCrop.on('update.croppie', function (ev, data) {
		$uploadCrop.croppie('result', {
			type: 'base64',
			format: format,
			size: result_size,
			quality: 0.8,
      backgroundColor: backgroundColor
		}).then(function (resp) {
			$('.croppie_img_data', target_element).val(resp);
			$('.remove-croppie', target_element).removeClass('hide');
		});
	});
	$('.crop_uploader', target_element).on('change', function () { readFile(this); });
	$('.remove-croppie', target_element).on('click', function(){
		$('.crop_upload', target_element).removeClass('hide');
		$('.crop_uploader', target_element).val('');
		$('.croppie_img_data', target_element).val('null');
		$('.crop_upload_canvas_wrap', target_element).removeClass('crop-size');
		$('.remove-croppie, .cropped_img, .crop_upload_canvas_wrap', target_element).addClass('hide');
	});
	if( typeof target_element.attr('data-g365_croppie_img_url') != 'undefined' && target_element.attr('data-g365_croppie_img_url') != '' ) {
		$('.crop_upload', target_element).addClass('hide');
		$('.remove-croppie', target_element).removeClass('hide');
    $('.cropped_img', target_element).append( '<img src="' + target_element.attr('data-g365_croppie_img_url') + '" />' );
	}
}
//start a data point claim
function g365_claim_start_v2( field_type, request_target, pl_name, formData){
  if( arguments.length !== 4) return 'Need array of the correct size to claim.' + typeof arguments + ':' + arguments.length;
	//to support use of ajax for getting data_set
  var defObj = $.Deferred();
  var data_set = {};
  data_set[ field_type ] = {
    proc_type : 'claim_data',
    ids : request_target,
    user_info: formData
  }
  console.log(data_set)
  g365_serv_con( data_set )
  .always( function(response) {
    defObj.resolve(response);
  });
  return defObj.promise();
} 

function createClaimModal(name, form_field_type, form_selected_request, ls_target) {
 //the format of the info i began with in this functino pl_name, form_field_type, form_selected_request
  var modalBG = document.createElement('div');
  var form = document.createElement('form');
  var modalHeading = document.createElement('h4');
  var nameLabel = document.createElement('label');
  var nameInput= document.createElement('input');
  var phoneLabel = document.createElement('label');
  var phoneInput = document.createElement('input');
  var relationLabel = document.createElement('label');
  var relationInput= document.createElement('input');
  var plbirthdayLabel = document.createElement('label');
  var plbirthdayInput= document.createElement('input');
  var btnContainer = document.createElement('div')
  var confirmBtn = document.createElement('input');
  var cancelBtn = document.createElement('button');
  var formElementArr;
  
//   place buttons in div for easier positioning
  confirmBtn.innerText = 'Request';
  
  var cleanName = name.split('REQUEST ACCESS');
  
  modalHeading.innerText = `Request Access for ${cleanName[0]}`;
  
  cancelBtn.innerText = 'Cancel';
  cancelBtn.addEventListener('click', deleteClaimModal);
  
  btnContainer.appendChild(confirmBtn);
  btnContainer.appendChild(cancelBtn);
  btnContainer.classList.add('button-container')
  
//   set label attr
  nameLabel.setAttribute('for','nameInput');
  nameLabel.innerText = 'Requester Name';
  phoneLabel.setAttribute('for','phoneInput');
  phoneLabel.innerText = 'Phone Number';
  relationLabel.setAttribute('for','relationInput');
  relationLabel.innerText = 'Relation to Player';
  plbirthdayLabel.setAttribute('for','plbirthdayInput');
  plbirthdayLabel.innerText = 'Player Birthday';
  
//   set input attr
  nameInput.setAttribute('type','text');
  nameInput.setAttribute('name','nameInput');
  nameInput.required = true;
  nameInput.setAttribute('placeholder', 'Full Name');
  nameInput.setAttribute('id','claimNameInput');
  nameInput.classList.add('claim-input')
  
  phoneInput.setAttribute('type','tel');
  phoneInput.setAttribute('name','phoneInput');
  phoneInput.required = true;
  phoneInput.setAttribute('placeholder', 'Phone Number');
  phoneInput.setAttribute('id','claimPhoneInput');
  phoneInput.classList.add('claim-input')
  
  relationInput.setAttribute('type','text');
  relationInput.setAttribute('name','relationInput');
  relationInput.required = true;
  relationInput.setAttribute('placeholder', 'Relation to Player');
  relationInput.setAttribute('id','claimRelationInput');
  relationInput.classList.add('claim-input')
  
  plbirthdayInput.setAttribute('type','date');
  plbirthdayInput.setAttribute('name','plbirthdayInput');
  plbirthdayInput.setAttribute('value','');
  plbirthdayInput.required = true;
  plbirthdayInput.setAttribute('placeholder', 'yyyy-mm-dd');
  plbirthdayInput.setAttribute('id','claimPlbirthdayInput');
  plbirthdayInput.setAttribute('min','1900-01-01');
  plbirthdayInput.setAttribute('max','2099-01-01');
  plbirthdayInput.setAttribute('onChange','claimPlbirthdayInput');
  plbirthdayInput.classList.add('claim-input')
  
  
  confirmBtn.setAttribute('type', 'submit');
  confirmBtn.setAttribute('value', 'Submit');
  confirmBtn.classList.add('button');
  cancelBtn.classList.add('button');
  
//   bundle form elements to loop over form element array to create modal   
  
 formElementArr = [modalHeading,nameLabel,nameInput,phoneLabel,phoneInput,relationLabel,relationInput,plbirthdayLabel,plbirthdayInput, btnContainer];

 formElementArr.forEach(element => {
    form.appendChild(element);
  })
  
  
 confirmBtn.addEventListener('click', function(e) {
    e.preventDefault();
   
//     check validity
   var claimInputs = document.querySelectorAll('#checkoutClaimForm .claim-input');
   var claimError = 0;
   claimInputs.forEach(input => {
     if(input.checkValidity() == false) {
       claimError += 1;
     }
   })
   
   if(claimError === 0 ) {
    // Grabbing the values from the input fields
        var nameValue = document.getElementById('claimNameInput').value;
        var phoneValue = document.getElementById('claimPhoneInput').value;
        var relationValue = document.getElementById('claimRelationInput').value;
        var plbirthValue = document.getElementById('claimPlbirthdayInput').value;

       // Inserting the values into an array
        var formData = [nameValue, phoneValue, relationValue, plbirthValue];

    //     console.log(formData);

       //send request to grassroots for claiming
    //    g365_claim_start_v2( form_field_type, form_selected_request, name, formData );

      $.when( g365_claim_start_v2( form_field_type, form_selected_request, name, formData ) ).done( function(claim_result){
        console.log(claim_result);
        //handle results remove modal
          var form = document.querySelector('#checkoutClaimForm');
          form.remove();

    //     display success
          var msg = document.createElement('p');
          var msgText;

          msg.classList.add('claim-message');


          msgText = 'Your request to access this account has been submitted. You will be notified via email when the request has been approved. If you have any additional questions or need further assistance, please contact our customer service team <a target="_blank" href="https://sportspassports.com/contact/">here</a>.';
          var msgNoLink = 'Your request to access this account has been submitted. You will be notified via email when the request has been approved. If you have any additional questions or need further assistance, please contact our customer service team.';
        
          msg.innerHTML = msgText;
          console.log(msg);
          document.querySelector('.modal__confirm--outer').appendChild(msg);

          setTimeout(() => {
            document.querySelector('#g365_pl_cp_form_wrap .ls_container').append(msgNoLink);
            document.querySelector('.modal__confirm--outer').remove();
          }, 8000);
          });

   }

   
 }); 
  
  modalBG.classList.add('modal__confirm--outer')
  form.setAttribute('id', 'checkoutClaimForm');
  
  modalBG.appendChild(form);
  document.body.appendChild(modalBG);
}
  
  
function displayClaimMessage(type) {
  var msg = document.createElement('p');
  var msgText;
  
  msg.classList.add('claim-message');
    
  if (type == 'success') {
    msgText = 'Your claim request has been succesfully submitted.';
  } else {
    msgText = 'Your claim request was unable to submit, please try again';
  }
  
  document.body.appendChild(msg);
  
  setTimeout(() => {
    document.querySelector('.claim-message').remove();
  }, 5000);
}
  
function deleteClaimModal(e) {
  e.preventDefault();
  var modal = document.querySelector('.modal__confirm--outer');
  modal.remove();
}

function openClaimModal(name, form_field_type, form_selected_request, ls_target) {
  createClaimModal(name, form_field_type, form_selected_request, ls_target);
  console.log('claim modal');
}
  
//server connection function
g365_serv_con = function( data_set, settings ){
  var send_data = {
    g365_session : g365_sess_data.id,
    g365_token : g365_sess_data.token,
    g365_time : g365_sess_data.time,
    g365_admin_key : ( typeof g365_sess_data.admin_key !== 'undefined' ) ? g365_sess_data.admin_key : null,
    g365_data_set : data_set,
    g365_settings : ( g365_serv_con.arguments.length === 2 ) ? settings : null
  };
  return $.ajax({
    type: "post",
    url: g365_script_ajax,
    cache: false,
    headers: {'X-Requested-With': 'XMLHttpRequest'},
    data: send_data,
    dataType: "json"
  });
}
//start a data point claim
function g365_claim_start( field_type, request_target ){
  console.log('hi');
  if( arguments.length !== 2) return 'Need array of the correct size to claim.' + typeof arguments + ':' + arguments.length;
	//to support use of ajax for getting data_set
	var defObj = $.Deferred();
  var data_set = {};
  data_set[ field_type ] = {
    proc_type : 'claim_data',
    ids : request_target
  }
  g365_serv_con( data_set )
  .always( function(response) {
    defObj.resolve(response);
  });
  return defObj.promise();
}

function g365_manage_additional_data( additional_target_base, additional_data ){
  //save the reference to the original element
  var additional_target = additional_target_base;
  var add_target_type = null;
  var additional_target_ele = null;
  if( additional_target instanceof jQuery ) {
    add_target_type = additional_target.attr('data-g365_load_target');
    console.log('check me', add_target_type, additional_target);
    //where we are going to put these addition(s)
    additional_data = additional_target.attr('data-g365_additional_data');
    //if its a select field then grab the data off the option
    if( additional_target.is('select') ) additional_data = additional_target.children('option:selected').attr('data-g365_additional_data');
    //if this is a multi-element, don't try to update the target
    if( typeof add_target_type !== 'undefined' && add_target_type.charAt(0) !== '.' ) {
      additional_target = additional_target.attr('data-g365_additional_target');
      //make sure we have something to process
      if( typeof additional_target !== 'undefined' ){
        //split to get our variables
        additional_target = additional_target.split(',');
        //first position is the write target
        additional_target_ele = $( '#' + additional_target[0] );
        if( additional_target_ele.length === 0 ) return;
        //all the rest of the positions are contributors
        additional_target.shift();
        //if the data is null, reset everything and exit
        if( additional_data === 'null' || additional_data === null || additional_data === '' || typeof additional_data === 'undefined') {
          additional_target_ele.parent().addClass('hide');
          additional_target_ele.html('');
          return;
        }

      } else {
        console.log('No additional data to process.');
        return;
      }
    }
  }
  //switch quotes if needed
  if( $.trim(additional_data).charAt(1) === "'" ) additional_data = additional_data.replace(/'/g, '"');
  //process incoming data
  if( additional_data !== '' ) additional_data = JSON.parse(additional_data);
  if( typeof additional_data !== 'object' ) return false;
  //if we have a bunch of elements
  if( typeof add_target_type !== 'undefined' && add_target_type.charAt(0) === '.' ) {
    //only look at elements in the same form
    add_target_type = $( add_target_type, additional_target_base.closest('form') );
    //loop through all elements and add the listeners for locking
    if( add_target_type.length > 0 ) {
      //clean up any listeners that are hanging around
      add_target_type.each(function(dex, target_element){
        //make a jQuery object to the target_element
        target_element = $(target_element);
        //because jQuery for some reason includes a bunch of nodes that I didn't ask for
        if(parseInt(target_element.data('index')) < 3) return;
        //remove old handlers
        target_element.off('change.additional_data');
        var sub_target_key = target_element.attr('data-g365_additional_target');
        //attach a listener to build the field
        target_element.on('change.additional_data', { add_target: $('#' + target_element.attr('data-g365_load_target')), add_data: additional_data, add_data_key: sub_target_key }, g365_additional_data_handler).trigger('change.additional_data');

      });
    } else {
      console.log('No targets to update.');
    }
  } else {
    //clean up any listeners that are hanging around
    $.each(additional_target, function(dex, val){
      //check that the element exists
      if( typeof val === 'object' ) return;
      var additional_target_contributor = $( '#' + val );
      if( additional_target_contributor.length === 0 ) return;
      //remove any previous handlers and data
      additional_target_contributor.off('change.additional_data');
      //attach a listener to build the field
      additional_target_contributor.on('change.additional_data', { add_target: additional_target_ele, add_data: additional_data }, g365_additional_data_handler).change();
    });
  }
}
function g365_additional_data_handler( e_data ){
  var additional_data_key = $(this).val();
  var additional_data = e_data.data.add_data;
  var additional_target_ele = e_data.data.add_target;
  var additional_options = '';
  //see if there isn't a button that needs to be revealed
  g365_manage_add_button($(this));
  //if there isn't data to add to the field, erase everything
  if( additional_data_key === '' || additional_data_key === null || additional_data_key === 'null' || typeof additional_data[additional_data_key] !== 'object' ) {
    additional_target_ele.parent().addClass('hide');
    additional_target_ele.html('').prop('disabled', true);
    return false;
  }
  //need to work off a pre-ordered list because the keys get reordered alpha (incorrectly)
  $.each(g365_division_key, function( key, key_name ) {
    if( key in additional_data[additional_data_key] ) {
      additional_options += '<option value="' + key + '" data-g365_short_name="' + key + '" data-g365_add_data="' + additional_data[additional_data_key][key] + '">' + key + '</option>';
    }
  });
  if( additional_options === '' ) {
    additional_target_ele.parent().addClass('hide');
    additional_target_ele.html('').prop('disabled', true);
    return false;
  }
  //with the default option
  additional_target_ele.attr('data-g365_og_val', '').html( '<option value="">-- Please Select</option>' + additional_options );
//   additional_target_ele.attr('data-g365_og_val', '').html( additional_options );
  if( additional_target_ele.attr('data-g365_select') !== '' ) {
    $('option[value="' + additional_target_ele.attr('data-g365_select') + '"]', additional_target_ele).prop('selected', true);
  }
  additional_target_ele.parent().removeClass('hide');
  additional_target_ele.prop('disabled', false);
}

function g365_cross_check_reqs( target ) {
  //opening vars
  var contributions_compile = {};
  var contributions_short_name = {};
  var contributions_compile_req = {};
//   var contributions_additional_data = {};
  //get contributions
  var contributions = target.attr('data-g365_contributors');
  if( typeof contributions === 'undefined' ) return null;
  //set error status
  var error = false;
  //parse
  contributions = contributions.split('|');
  //try to get optional reqiured element list
  var contributions_req = target.attr('data-g365_contributors_req')
  //set it empty or parse into array
  contributions_req = ( typeof contributions_req === 'undefined' ) ? [] : contributions_req.split('|');
  //loop through and check for vaild contributions
  $.each(contributions, function(dex, cont_id){
    //each entry is an id
    var contribution_part = $( '#' + cont_id );
    //get the value of the contribution
    contributions_compile[cont_id] = contribution_part.val();
    //test for requirement, set error if there is an issue
    if( contributions_req.length > 0 && ($.inArray( cont_id, contributions_req ) > -1) && (typeof contributions_compile[cont_id] === 'undefined' || contributions_compile[cont_id] === '' || contributions_compile[cont_id] === null) ) {
      var error_target = contribution_part;
      //add error to individual elements with a self cancelling highlight
      if( typeof error_target.attr('data-g365_error_target') !== 'undefined' && $('#' + error_target.attr('data-g365_error_target')).length === 1 ) error_target = $('#' + contribution_part.attr('data-g365_error_target'));
      error_target.addClass('g365_error').parent().addClass('error_parent');
      error_target.on('focus.g365_error', function(){
        var error_self = $(this);
        error_self.parent().removeClass('error_parent');
        error_self.off('focus.g365_error');
      });
      error = true;
    }

    //pass along the attachment data if needed
    if( contribution_part.attr('data-g365_send_additional') === 'true' && typeof contribution_part.attr('data-g365_additional_data') != 'undefined' && contribution_part.attr('data-g365_additional_data') != '') {
      contributions_compile['divisions'] = contribution_part.attr('data-g365_additional_data');//.replace(/"/g, '&quot;');//$('<div />').text(contribution_part.attr('data-g365_additional_data')).html();
    }

    //add required to a separate object
    if( $.inArray( cont_id, contributions_req ) > -1 ) contributions_compile_req[cont_id] = contribution_part.val();
    //special instructions for select elements
    if( contribution_part.is('select') ) {
      //if the select element doesn't have any options unset the error
      if( contribution_part.children().length === 0 ) {
        error = false;
      } else {
        //otherwise grab some info off the selected option
        var contribution_part_option = $('option:selected', contribution_part);
        //get the short names if they are present
        contributions_short_name[cont_id] = (typeof contribution_part_option.attr('data-g365_short_name') === 'undefined') ? ((contribution_part_option.val() === '' || contribution_part_option.val() === 'new') ? '' : contribution_part_option.text()) : contribution_part_option.attr('data-g365_short_name');
        //if we have additional data add it to the dataset
        if( typeof  contribution_part_option.attr('data-g365_ref_val') !== 'undefined' && contribution_part_option.attr('data-g365_ref_val') !== '' ) {
          var cont_date = new Date();
          //which school year
          var cont_target_year = (cont_date.getMonth() < 8) ? cont_date.getFullYear()-1 : cont_date.getFullYear();
          //divison
          var cont_division = parseInt(contribution_part_option.attr('data-g365_ref_val'));
          //add the minimum birth year for players
          contributions_compile[cont_id + '_birth_lock'] = ">" + (cont_target_year - cont_division) + "-07-30";
//           contributions_compile[cont_id + '_birth_lock'] = "> '" + (cont_target_year - cont_division) + "-07-30'"; // + '-OR'
          //add the minimum class for players
          contributions_compile[cont_id + '_class_lock'] = '>' + (cont_target_year + 18 - cont_division); //  + '-OR'
          contributions_compile['roster_lock_type'] = 0; //default
        }
        //if we have additional exception info add it
        if( typeof contribution_part_option.attr('data-g365_add_data') !== 'undefined' && contribution_part_option.attr('data-g365_add_data') !== '' ) {
          //add the lock type
          contributions_compile['roster_lock_type'] = contribution_part_option.attr('data-g365_add_data');
        }
      }
      //if we needed to send the options of a select, probably deprecated
      if( contribution_part.attr('data-g365_send_options') === 'true' && contribution_part.children('option').length ) {
        if( contribution_part.children().first().html().indexOf("Please") != -1 ) contribution_part.children().first().remove();
        contributions_compile[contribution_part.attr('id') + '_options'] = contribution_part.html();
      }
    } else {
      //for all other elements grab the info
      contributions_short_name[cont_id] = (typeof contribution_part.attr('data-g365_short_name') === 'undefined') ? contribution_part.val() : contribution_part.attr('data-g365_short_name');
    }
    if( error ) return false;
  });
  if( error ) return false;
  return {data: contributions_compile, names: contributions_short_name, req: contributions_compile_req};
//   return {data: contributions_compile, names: contributions_short_name, additional_data: contributions_additional_data};
}

//add functionality for form dependancy
function g365_contingent_manager( caller ) {
  var target = $( '#' + caller.attr('data-g365_contingent') );
  if( target.length !== 1 ) return 'Cannont find target.';
  var the_val = caller.val();
  //make the var to target all contingent divs
  var target_children = target.children('div');
  //if it's a checkbox handle it differently
  if( caller.prop('type') === 'checkbox' ) {
    if( caller.prop('checked') === true ) {
      $(':input', target_children).prop('disabled', false).prop('required', true);
      target.removeClass('form-disabled').slideDown();
    } else {
      var sub_target = $(':input', target_children); //.prop('disabled', true).prop('required', false).val('');
      sub_target.each(function(){
        $(this).prop('disabled', true).prop('required', false).val('');
      });
      target.addClass('form-disabled').slideUp();
    }
  } else {
    //other input fields depend on the data being entered by the user
    if( typeof the_val === 'undefined' || the_val === '' || the_val === 'new' || the_val === 'null' || the_val === null ) {
      $(':input', target_children).prop('disabled', true);
      target.addClass('form-disabled').slideUp();
  //     target.addClass('form-disabled');
    } else {
      $(':input', target_children).prop('disabled', false);
      target.removeClass('form-disabled').slideDown();
  //     target.removeClass('form-disabled');
    }
  }
}

//grade order
var g365_grade_key_order = [
  "30",
  "29",
  "28",
  "27",
  "26",
  "25",
  "24",
  "23",
  "22",
  "21",
  "20",
  "19",
  "18",
  "17",
  "16",
  "15",
  "14",
  "13",
  "12",
  "11",
  "10",
  "9",
  "8",
  "7",
  "6",
  "5",
  "4",
  "3",
  "2",
  "1",
  "60",
  "59",
  "58",
  "57",
  "56",
  "55",
  "54",
  "53",
  "52",
  "51",
  "50",
  "49",
  "48",
  "47",
  "46",
  "45",
  "44",
  "43",
  "42",
  "41",
  "40",
  "39",
  "38",
  "37",
  "36",
  "35",
  "34",
  "33",
  "32",
  "31",
];
//grade label key
var g365_grade_key = {
  "30" : "Level 30",
  "29" : "Level 29",
  "28" : "Level 28",
  "27" : "Level 27",
  "26" : "Level 26",
  "25" : "Level 25",
  "24" : "Level 24",
  "23" : "Level 23",
  "22" : "Level 22",
  "21" : "Level 21",
  "20" : "Level 20",
  "19" : "Level 19",
  "18" : "Level 18",
  "17" : "17U / Varsity",
  "16" : "16U / JV",
  "15" : "15U / Frosoph",
  "14" : "14U / 8th Grade",
  "13" : "13U / 7th Grade",
  "12" : "12U / 6th Grade",
  "11" : "11U / 5th Grade",
  "10" : "10U / 4th Grade",
  "9" : "9U / 3rd Grade",
  "8" : "8U / 2nd Grade",
  "7" : "Level 7",
  "6" : "Level 6",
  "5" : "Level 5",
  "4" : "Level 4",
  "3" : "Level 3",
  "2" : "Level 2",
  "1" : "Level 1",
  "60" : "Level 30G",
  "59" : "Level 29G",
  "58" : "Level 28G",
  "57" : "Level 27G",
  "56" : "Level 26G",
  "55" : "Level 25G",
  "54" : "Level 24G",
  "53" : "Level 23G",
  "52" : "Level 22G",
  "51" : "Level 21G",
  "50" : "Level 20G",
  "49" : "Level 19G",
  "48" : "Level 18G",
  "47" : "Varsity Girls",
  "46" : "JV Girls",
  "45" : "Frosoph Girls",
  "44" : "Girls 8th Grade",
  "43" : "Girls 7th Grade",
  "42" : "Girls 6th Grade",
  "41" : "Girls 5th Grade",
  "40" : "Girls 4th Grade",
  "39" : "Level 9G",
  "38" : "Level 8G",
  "37" : "Level 7G",
  "36" : "Level 6G",
  "35" : "Level 5G",
  "34" : "Level 4G",
  "33" : "Level 3G",
  "32" : "Level 2G",
  "31" : "Level 1G",
  "74" : "Frosh/Soph",
  "75" : "JV",
  "76" : "Varsity",
};
//division label and order key
var g365_division_key = {
  "Open" : "Open",
  "Open East" : "Open East",
  "Open West" : "Open West",
  "Gold" : "Gold",
  "Gold East" : "Gold East",
  "Gold West" : "Gold West",
  "Gold North" : "Gold North",
  "Gold South" : "Gold South",
  "Silver" : "Silver",
  "Silver East" : "Silver East",
  "Silver West" : "Silver West",
  "Silver North" : "Silver North",
  "Silver South" : "Silver South",
  "Bronze" : "Bronze",
  "Bronze East" : "Bronze East",
  "Bronze West" : "Bronze West",
  "Bronze North" : "Bronze North",
  "Bronze South" : "Bronze South",
  "Copper" : "Copper",
  "Copper East" : "Copper East",
  "Copper West" : "Copper West",
  "Copper North" : "Copper North",
  "Copper South" : "Copper South",
  "ABT" : "ABT",
  "Baller TV" : "Baller TV",
  "Destination Irvine" : "Destination Irvine",
  "Kangaroo" : "Kangaroo",
  "OCYSF" : "OCYSF",
  "Passport" : "Passport",
  "SCIBCA" : "SCIBCA",
  "Circuit" : "Circuit",
  "Do Not Show" : "Do Not Show",
  "" : "",
};

//scrub the dependance chain
function revert_dependance_chain(ele){
  if( ele.length === 0 ) return;
  if( ele.is( 'form' ) ) {
    ele.children('.error, .success').remove();
  } else {
    ele.parent().children('.error, .success, .new_form').remove();
    if( ele.attr('data-g365_static') === "true") {
      ele.prop("selectedIndex", 0);
    } else {
      if( ele.is(':text') ) {
        ele.val('');
      } else {
        ele.html('');
      }
    }
    g365_contingent_manager(ele);
  }
  //if we have an add button, manage it
  g365_manage_add_button( ele );
//   var add_button = ele.attr('data-g365_add_button');
//   if( typeof add_button !== 'undefined' ) $('#' + add_button).hide();
  var reset_target = $('#' + ele.attr('data-g365_load_target') );
  if( reset_target.length === 0 ) {
    reset_target = $('#' + ele.attr('data-g365_reset_target') );
    if( reset_target.length !== 0 ) ele.trigger('resetter');
  }
  revert_dependance_chain( reset_target );
}

//assemble select list from local object on the select option
function g365_build_dropdown_from_object( target ) {
  //see if we are doing an id or a class
  var load_target = target.attr('data-g365_load_target');
  if( typeof load_target === 'undefined' || load_target === '' ) return 'Need load target to build.';
  //check data to build with too
  var the_data = ( target.is('select') ) ? target.children('option:selected').attr('data-g365_additional_data') : target.attr('data-g365_additional_data');
//   console.log('piece', target, load_target, the_data);
  //if we are building for a class figure it out now
  if( load_target.charAt(0) === '.' ) {
    //only look at elements in the same form
    load_target = $(load_target, target.closest("form"));
  } else {
    //grab the element we are trying to administer
    load_target = $('#' + load_target);
    if( load_target.length !== 1 ) return 'Bad load target reference.';
  }
  //exit if we are missing stuff
  if( typeof the_data === 'undefined' || the_data === '' ) {
    load_target.html('').change();
    load_target.parent().addClass('hide');
    return;
  }
  load_target.each(function (){revert_dependance_chain($(this))});
//   revert_dependance_chain(load_target);
  the_data = JSON.parse($.trim(the_data.replace(/\'/g, '"')));
  var newContent = '<option value="">-- Please Select</option>';
  $.each(the_data, function( key, levels ) {
    var option_title = g365_grade_key[key];
    var option_additional = ((levels == 0) ? '' : ' data-g365_additional_data="' + (JSON.stringify(levels).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')) + '"');
    newContent += '<option value="' + key + '" data-g365_ref_val="' + key + '"' + option_additional + '>' + ((typeof option_title === 'undefined') ? key : option_title) + '</option>';
  });
  load_target.each(function(){
    var load_tar = $(this);
    if( typeof load_tar.attr('data-g365_select') !== 'undefined' ) {
      load_tar.html(newContent);
      $('option[value="' + load_tar.attr('data-g365_select') + '"]', load_tar).prop('selected', true);
      load_tar.change();
      //add a hook for disabled inputs, if their parent or parent's parent has the class 'form-disabled' disable this element too
      if( load_tar.prop('disabled') === true || load_tar.parent().hasClass('form-disabled') || load_tar.parent().parent().hasClass('form-disabled') ) load_tar.prop('disabled', true);
    } else {
      load_tar.html(newContent).change();
    }
    //if we are hiding a field keep it hidden
    if( load_tar.parent().attr('data-g365_link_target_dest') !== 'hide' ) load_tar.parent().removeClass('hide');
  });
  //add dependant additions
  g365_manage_additional_data( target );
  g365_manage_add_button( target );
}

//toggle add buttons based on a single source
function g365_manage_add_button(target){
  var add_button = target.attr('data-g365_add_button');
  if( typeof add_button !== 'undefined' ) {
    var the_val = ( target.is('select') ) ? $('option:checked', target).val() : target.val();
    //if we don't have a value to use then hide the add button
    if( typeof the_val === 'undefined' || the_val === '' || the_val === null || the_val === 'new' || the_val === 'undefined' ) {
      $('#' + add_button).hide();
    } else {
      $('#' + add_button).show();
    }
  }
}

//assemble select list from data
function g365_build_dropdown_from_data( target ) {
// <select id="team_level" class="data_loader" data-g365_type="team_names" data-g365_load_target="team_selector" data-g365_contributors="club_id">
  //get all the manitory variables
  var newContent = '';
  var target_id = target.attr('id');
  var the_val = target.val();
  var load_target = $('#' + target.attr('data-g365_load_target'));
  //revert all the subsequent dependancies
  revert_dependance_chain(load_target);
  var type = target.attr('data-g365_type');
  var result_limit = target.attr('data-g365_limit');
  //if we have an add button, manage it
  var add_button = target.attr('data-g365_add_button');
  if( typeof add_button !== 'undefined' ) {
    //if we don't have a value to use then hide the add button
    if( the_val === '' || the_val === null || the_val === 'new' ) {
      $('#' + add_button).hide();
    } else {
      $('#' + add_button).show();
    }
  }
  //if there was an error set, remove it in preparation for the next try
  if( target.next().hasClass('error') || target.next().hasClass('success') ) target.next().remove();
  //after clearing the errors, exit if it's a new record
  if( the_val === 'new' ) {
    g365_build_form_from_data( target );
    return false;
  }
  //if we are getting missing or malformed data, exit.
  if( target.prop('disabled') === true || target.length !== 1 || the_val === '' || the_val === null || typeof the_val === 'undefined' || typeof type === 'undefined' || typeof target_id === 'undefined' || load_target.length !== 1 ) return false;
  //see if we need to collect requirement data
  var contributions_compile = g365_cross_check_reqs( target );
  if( contributions_compile === false ) {
    target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Missing required field data.</p>');
    return false;
  }
  //make the val an array before we potentially change it to null
  the_val = the_val.split('|');
  //if one of the contributions is the calling field itself, we are doing a search without specific ids, disregard its value
  if( contributions_compile !== null && !$.isEmptyObject(contributions_compile.data) && ($.inArray( target_id, $.map(contributions_compile.data, function(el,key){ return key; }) ) > -1) ) the_val = null;
  
  //setup ajax data object
  var data_set = {};
  data_set[type] = {
    proc_type : 'get_data',
    ids : the_val,
    contributions: ( contributions_compile === null ) ? null : contributions_compile.data,
    limit: (typeof result_limit !== 'undefined') ? result_limit : ''
  }
  g365_serv_con( data_set )
  .done( function(response) {
    console.log(response);
    if (response.status === 'success') {
      //to reset any target
      newContent += '<option value="">-- Please Select</option>';
      //add new data if allowed
      newContent += (target.attr('data-ls_no_add') === "true") ? '' : '<optgroup label="New"><option value="new">Create New</option></optgroup>';
      var newContentOptions = '';
      //if we have an array, loop through template variables replacing each with data from the query
      $.each(response.message[type], function(id_key, vals){
        newContentOptions += '<option value="' + vals.id + '" data-g365_short_name="' + vals.short_name + '">' + vals.title + '</option>';
      });
      if( newContentOptions !== '' ) newContent += '<optgroup label="Existing">' + newContentOptions + '</optgroup>';
    } else {
      console.log('false');
      newContent = '';
      target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">' + response.message + '</p>');
    }
  })
  .fail( function(response) {
    console.log('error', response);
    newContent = '';
    target.parent().append( response.responseText );
  })
  .always( function(response) {
    //post respose
    load_target.html( newContent );
    //if there is an error on the field that we just posted to, clear it too
  });
}

//load presets into the form
function g365_add_presets( form_template, preset_compile ) {
  var element_id_key = form_template.attr('id').replace('_fieldset', '');
  if( $.isPlainObject(preset_compile) === false || typeof element_id_key === 'undefined' ) return form_template;
  $.each( preset_compile.data, function( key, key_val ){
    var preset_element = $( '#' + element_id_key + '_' + key, form_template );
    if( preset_element.length !== 1 ) return;
    preset_element.val(key_val);
    if( typeof preset_compile.names !== 'undefined' ) preset_element.attr('data-g365_short_name', preset_compile.names[key]);
    if( (preset_compile.hide && preset_compile.hide[key]) || preset_element.attr('type') === 'hidden' ){
      preset_element.parent().hide();
    }
    if( preset_element.is('select') ) {
      console.log('I is select');
    }
  });
  if( typeof preset_compile.attrs !== 'undefined' ) $.each( preset_compile.attrs, function( key, key_val ){
    var preset_element = $( element_id_key, form_template );
    form_template.attr(key, key_val);
  });
  return form_template;
}

//assemble form from data
function g365_build_form_from_data( target ) {
  var type = ( target.attr('data-g365_type_new') ) ? target.attr('data-g365_type_new') : target.attr('data-g365_type');
  var target_id = target.attr('id');
  var load_target_id = target.attr('data-g365_form_dest');
  var load_target = $('#' + load_target_id + '_data');
  //if we are missing the load target, we can create right after the form.
  if( load_target.length === 0 ) load_target = $( '<div class="new_form" id="' + load_target_id + '_data"></div>' ).appendTo( target.parent() );
  //exit if we don't have what we need or are malformed
  if( target.length !== 1 || typeof type === 'undefined' || typeof target_id === 'undefined' ) return false;

  //evaluate the target and it's value
  var the_val;
  var id_val;
  //add a pointer to the data (id) that controls the form creation
  if( typeof target.attr('data-g365_data_target') === 'undefined' ) {
    the_val = target.val();
    id_val = target.attr('id');
    var origin_id = id_val;
  } else {
    the_val = target.attr('data-g365_data_target');
    var new_target = $( '#' + the_val ); 
    if( new_target.length === 1 ) {
      id_val = new_target.attr('id');
      the_val = new_target.val();
    } else {
      id_val = null;
      the_val = null;
    }
  }
  //adds a click on 'data-g365_select_click' target at the end of the process
//   if( typeof data.searchField.attr('data-g365_select_click') === 'string' ) $('#' + data.searchField.attr('data-g365_select_click')).click();
  //if the val is not 'new' parse the data id
  the_val = ( the_val === null || the_val === 'new' || the_val === '' || typeof the_val === 'undefined' ) ? null : parseInt(the_val);
  //we need the val to proceed
  if( isNaN(the_val) ) return false;
  //if there was an error set, remove it in preparation for the next try
  if( target.next().hasClass('error') || target.next().hasClass('success') ) target.next().remove();
  //also if the field is a one off, clear it
  if( target.attr( 'data-g365_action' ) === 'load_form' && typeof target.attr('data-g365_form_template_new') !== 'undefined' ) {
    target.val('');
  }
  //see if we need to collect requirement data
  var contributions_compile = g365_cross_check_reqs( target );
  if( contributions_compile === false ) {
    target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Missing required field data.</p>');
    return false;
  }

  //make sure that we aren't running into any limitations on the add
  var limits = target.attr('data-g365_limit');
  var dropdown = false;
  var dropdown_select = false;
  var dropdown_id;
  if( typeof limits !== 'undefined') {
    var break_out = false;
    limits = limits.split('|');
    $.each(limits, function(dex, limit_key){
      var limit_vals = limit_key.split(',');
      switch( limit_vals[0] ){
        case 'max':
          if( load_target.children().length >= limit_vals[1] ) {
            target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Maximum reached.</p>');
            break_out = true;
            return false;
          }
          break;
        case 'exceptions':
          var limit_val = (limit_vals[1] === 0) ? 0 : 2;
          if( limit_val !== 0 && load_target.children('[data-g365_exception]').length >= limit_val ) {
            target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Exception maximum reached.</p>');
            break_out = true;
            return false;
          }
          break;
        case 'dropdown':
          dropdown = $('#' + limit_vals[1]);
          if( dropdown.is('select') ) {
            var dropdown_keys = load_target.children('[data-g365_dropdown_key]').map( function(){ return $(this).attr('data-g365_dropdown_key'); } ).get();
            dropdown_select = dropdown.children('option:selected');
            if( dropdown_select.length !== 1 ){
              target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Cannot find unique listing.</p>');
              break_out = true;
              return false;
            } else {
              dropdown_id = dropdown_select.html();
              var dupe = false;
              $.each(dropdown_keys, function(dex,key_val){ if( key_val === dropdown_id ) dupe = true; return false; });
              if( dropdown_keys.length > 0 && dupe ) {
                target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Event slot already in use.</p>');
                break_out = true;
                return false;
              }
            }
          }
          break;
        case 'only':
          //get the vars to use in the 'only' lock
          var only_fields = [];
          $.each(limit_vals.slice(1), function(dex, val){only_fields[only_fields.length] = val;});
          //get all the current data_key vars from the elements already in place
          var key_fields = {};
          load_target.children('.g365_form').each(function(){
            var this_fieldset = $(this);
            //use fieldset id to create unique reference
            var this_fieldset_id = this_fieldset.attr('id');
            key_fields[this_fieldset_id] = {};
            //collect all the data_keys for this fieldset
            $( '[data-g365_data_key]', this_fieldset).each(function(){
              var this_key = $(this);
              key_fields[this_fieldset_id][this_key.attr('data-g365_data_key')] = this_key.val();
            });
          });
          //loop through all the established fieldsets to see if we have any full matches
          $.each(key_fields, function(set_id, set_vars){
            var matched_sets = [];
            //loop through each datapoint for the fieldset and see if it matches something in contributions_compile.data
            if( contributions_compile !== null) $.each(only_fields, function(dex, key_id){if( typeof set_vars[key_id] !== 'undefined' && set_vars[key_id] == contributions_compile.data[key_id] ) matched_sets[matched_sets.length] = key_id;});
            //the matched set has the same number of entries as the locking set then we have a full match and we need to stop
            if( matched_sets.length === only_fields.length ) {
              target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Duplicate entries not allowed.</p>');
              break_out = true;
              return false;
            }
          });
          break;
      }
    });
    if( break_out ) return false;
  }
  //build the field_group var
  var field_group = type;
  if( contributions_compile !== null) $.each(contributions_compile.data, function(key, val){
    //if the set contains a 'new' value, ask for creation by setting the id to null
    if( val === 'new' ) the_val = null;
    //make a unique trail to each element out of the presets that we are assigning
    if( val !== "0" && $.isNumeric(val) && key !== id_val ) field_group += '_' + val;
  });
  //if the val is unusable create a unique id based on the current timestamp
  if( the_val === null ) {
//     field_group += '_' + (new Date().valueOf());
    if( target.is('select') ) target.slideUp();
  } else {
    the_val = [the_val];
  }
  //create the data set to get our form put together
  var form_data = {
    query_type : type,
    id : the_val,
    field_group: field_group,
    go_flat: false,
    template_format: (typeof target.attr('data-g365_form_template') === 'undefined') ? 'form_template_min' : target.attr('data-g365_form_template'),
    contributions: (( contributions_compile !== null) ? contributions_compile.data : null)
  }
  //if we have an origin to push back to include it
  if( typeof origin_id !== 'undefined' ) form_data.field_origin_id = origin_id;
  //if we are going flat, set the variable
  form_data.go_flat = target.attr('data-g365_base_id');
  if( typeof form_data.go_flat !== 'undefined' ) form_data.go_flat = ( form_data.go_flat === 'self' ) ? field_group : form_data.go_flat;
  $.when(g365_build_template_from_data( form_data ))
  .done( function(form_template_message){
    //clear any errors before we write to the set
    load_target.children('.error, .success').remove();
    //try to close all other fieldsets
    //if the other fieldsets didn't close, it's probably because there is an error somewhere in the fields. handle that here
    var closed = $('.change-title.g365-expand-collapse-fieldset' , load_target).trigger('click', 'closed');
    var form_new_loaded = '';
    //set a handle for the new form element, add presets, and attach the new form
    if( typeof form_template_message === 'object' ) {
      if( form_template_message.enabled === null && form_template_message.disabled === null ) {
        form_new_loaded = $( '<p>No data found.</p>' ).prependTo( load_target );
      } else {
        if( form_template_message.disabled === null ) {
          g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( load_target );
        } else if( form_template_message.enabled === null ) {
          g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( load_target );
        } else {
          //attach the new form for both disabled and enabled data points
          var enabled_div = $('.g365_enabled_data', load_target);
          if( enabled_div.length === 0 ) {
            load_target.html( g365_add_presets( $( form_template_message.disabled ), contributions_compile ) );
            load_target.html( g365_add_presets( $( form_template_message.enabled ), contributions_compile ) );
          } else {
            g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( $('.g365_enabled_data', load_target) );
            g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( $('.g365_disabled_data', load_target) );
          }
        }
        form_new_loaded = load_target;
      }
    } else {
      //attach the new form
//       form_new_loaded = $( form_template_message ).prependTo( $('.form-holder>form>div:first-child', current_form) );
      form_new_loaded = g365_add_presets( $( form_template_message ), contributions_compile ).prependTo( load_target );
    }
    //add the connector for the dropdown if we have one
    if( dropdown_select !== false ){
      form_new_loaded.attr('data-g365_dropdown_key', dropdown_id).attr('data-g365_dropdown_target', dropdown_select.parent().attr('id'));
      dropdown_select.prop('disabled', true);
      var drop_parent = dropdown_select.parent();
      var drop_enabled = drop_parent.children('option:enabled:not([value=""])');
      if(drop_parent.attr('data-g365_auto_advance') == 'true') {
        if( drop_enabled.length > 0 ) drop_enabled.first().prop('selected', true);
      } else {
        drop_parent.val('');
      }
      drop_parent.change();
      if( drop_enabled.length === 0 ) dropdown_select.closest('.form-holder').children(':not(.primary-form)').addClass('hide');
//       if( drop_enabled.length === 0 ) dropdown_select.closest('.form-holder').children(':not(.primary-form)').addClass('hide').parent().parent().children(':not(.form-holder)').addClass('hide');
      revert_dependance_chain( $( '#' + drop_parent.attr('data-g365_deps_start') ) );
    } else if( dropdown !== false ) {
//       dropdown.change();
    }
    if( typeof target.attr('data-g365_toggle_parent') !== 'undefined' ) {
      target.closest('.form-holder').children(':not(.primary-form)').addClass('hide');
      if( target.attr('data-g365_toggle_parent') !== 'true' ) $( '#' + target.attr('data-g365_toggle_parent') ).removeClass('hide');
    }
    //initialize form_data
    g365_form_start_up( form_new_loaded );
    //check to see if we should minify the formand if we should change the default afterwards
    if( target.attr('data-g365_form_load_min') === 'true' ) form_new_loaded.children('.form-collapse-title.g365-expand-collapse-fieldset').click();
    if( target.attr('data-g365_form_load_min') === 'true-false' ) {
      target.attr('data-g365_form_load_min', 'false');
      form_new_loaded.children('.form-collapse-title.g365-expand-collapse-fieldset').click();
    }
    if( typeof target.attr('data-g365_deps_start') !== 'undefined' ) {
      revert_dependance_chain( $( '#' + target.attr('data-g365_deps_start') ) );
    }
    //if this isn't nested show the submit buttons now that we have a form
    $( '#' + load_target_id + '_submit' ).show();
  });
}

function g365_sub_template_parser(data_key, data_id, data_val, form_data) {
  if( $.trim(data_val).charAt(1) === "'" ) data_val = data_val.replace(/'/g, '"');
  console.log('parser', data_key, data_id, data_val, form_data, g365_form_data.form[data_key]);
  data_val = $.parseJSON(data_val);
  if( typeof g365_form_data.form[data_key] === 'undefined') return 'No template hook found.';
  var sub_template = g365_form_data.form[data_key].form_template_input_item;
  var search_count = (((sub_template.match(/g365_livesearch_input/g) || [1] ).length)-1);
  var sub_collection = '';
  //find all the variables in the template
  var sub_contentVars = sub_template.match(/{{(.+?)}}/g);
  //call back function to eliminate duplicates
  sub_contentVars = sub_contentVars.filter( function (value, index, self) { return self.indexOf(value) === index; } );
  $.each(data_val, function(sub_id, sub_data){
    if( $.isPlainObject(sub_data) ) {
      if( typeof g365_form_data[data_key][sub_id] === 'undefined' ) g365_form_data[data_key][sub_id] = [];
      $.each(sub_data, function(sub_data_key, sub_datapoint){
        g365_form_data[data_key][sub_id][sub_data_key] = sub_datapoint;
      });
      if( typeof g365_form_data[data_key][sub_id]['id'] === 'undefined' ) g365_form_data[data_key][sub_id]['id'] = sub_id;
    }
    var go_flat = form_data.go_flat;
    //if the form is an init, then just use the query type
    switch( true ) {
      case (form_data.template_format === "form_template_init"):
        go_flat = form_data.query_type;
        //if the go_flat and the field_group are the same switch out the correct id for the '{{id}}'s 
        break;
      case (form_data.go_flat === form_data.field_group):
        go_flat = form_data.go_flat.replace('{{id}}', data_id); //.replace('{{field-set-id}}', data_id);
        break;
      case (form_data.field_group.indexOf('_{{id}}') !== false):
        go_flat = form_data.field_group.replace('_{{id}}', ('_' + data_id));
    }
    var sub_item = sub_template.replace(new RegExp('{{field-set-id-flat}}', 'g'), go_flat);
    if( typeof data_id === undefined || data_id === null || data_id === 'null' ) {
      sub_item = sub_item.replace(new RegExp('{{field-set-id}}', 'g'), form_data.query_type);
    } else {
      sub_item = sub_item.replace(new RegExp('{{field-set-id}}', 'g'), (form_data.query_type + '_' + data_id + (( form_data.field_group === form_data.query_type + '_{{id}}' ) ? '_' + sub_id : '')));
    }
    $.each(sub_contentVars, function(var_dex, var_name_brackets){
      var var_name = var_name_brackets.slice(2,-2);
      if( typeof g365_form_data[data_key][sub_id][var_name] !== 'undefined' ) sub_item = sub_item.replace(new RegExp(var_name_brackets, 'ig'), g365_form_data[data_key][sub_id][var_name]);
    });
    sub_item = sub_item.replace(/{{(.+?)}}/g, '');
    sub_collection += sub_item;
  });
  return sub_collection;
}

//assemble forms
//form_data = {query_type: "rosters", id: null, field_group: "search_rosters_21527434877266", template_format: "form_part_name", field_origin_id: "some_id", dropdown_target: "event_id"}
function g365_build_template_from_data( form_data ) {
  function g365_build_template_from_data_proc(newContent, data) {
    //build a form from the data and set 
    var newContent_all = '';
    var items_enabled = '';
    var items_disabled = '';
    var newContent_part = newContent;
    //change the order if we need it.
    if( data !== null && data.length > 1 ) {
      switch( form_data.query_type ) {
        case 'ro_ed':
        case 'to_ed':
        case 'rosters_club_sl':
        case 'rosters_sl':
        case 'rosters_event':
        case 'rosters':
        case 'rosters_teams':
        case 'tournament_roster_admin':
          var data_sort = [];
          //reorder data
          $.each(g365_grade_key_order, function(dex, level){
            $.each(g365_division_key, function(division, division_name){
              $.each(data, function(data_dex, data_id){
                if(
                  (g365_form_data[form_data.query_type][data_id].division == division ||
                  (typeof g365_form_data[form_data.query_type][data_id].division === 'undefined' && division === '') ) &&
                  g365_form_data[form_data.query_type][data_id].level == level ) data_sort.push(data_id);
              });
            });
          });
          if( data_sort.length > 1 ) data = data_sort;
          break;
      }
    }
    if( data === null ) {
//       items_enabled = ;
      //create form for this specific data point
      newContent_part = newContent;
      $.each(form_data.contributions, function(data_key, data_val){
        if( data_val === null ) return;
        //need more work to integrate this feature
        //check for json and build the required section
        if( data_val.toString().match(/^(\[|\{)/) ) {
          data_val = g365_sub_template_parser(data_key, form_data.field_group, data_val, form_data);
        }
        newContent_part = newContent_part.replace(new RegExp('{{' + data_key + '}}', 'ig'), data_val.toString().trim());
      });
      items_enabled = newContent_part;
    } else {
      $.each(data, function(data_dex, data_id) {
        var data_arr = g365_form_data[form_data.query_type][data_id];
        if( typeof data_arr === 'string' ) {
          items_enabled += '<div class="error">' + data_arr + '</div>';
          return;
        }
        //create form for this specific data point
        newContent_part = newContent;
        if( typeof g365_form_data.form[form_data.query_type].additional_parts !== 'undefined' ) {
          //get array of parts
          var parts = g365_form_data.form[form_data.query_type].additional_parts.split(',');
          $.each( parts, function(dx, val){ newContent_part = newContent_part.replace('{{' + val + '}}', ((typeof g365_form_data.form[form_data.query_type][val + data_arr.event] !== 'undefined') ? g365_form_data.form[form_data.query_type][val + data_arr.event] : '')); });
        }
        //add in values from the contributions (specifically tournament level locking vars)
        $.extend(data_arr, form_data.contributions);
        $.each(data_arr, function(data_key, data_val){
          if( data_val === null ) return;
          //check for json and build the required section
          if( data_val.toString().match(/^(\[|\{)/) ) {
            data_val = g365_sub_template_parser(data_key, data_id, data_val, form_data);
          }
          newContent_part = newContent_part.replace(new RegExp('{{' + data_key + '}}', 'ig'), data_val.toString().trim());
        });
        g365_form_data[form_data.query_type][data_id].form_template = newContent_part;
        if( g365_form_data[form_data.query_type][data_id].enabled !== '0' ) {
          items_enabled += newContent_part;
        } else {
          items_disabled += newContent_part;
        }
      });
    }
    if( items_enabled === '' ) items_enabled = null;
    if( items_disabled === '' ) items_disabled = null;
    return {'enabled': items_enabled, 'disabled': items_disabled};
	}
  console.log('build template', form_data);
	//to support the potential of ajax for getting data_set
	var defObj = $.Deferred();
	//get required content string, with a default
	var newContent = ( typeof form_data.template_format !== 'undefined' ) ? g365_form_data.form[form_data.query_type][form_data.template_format] : g365_form_data.form[form_data.query_type].form_template;
	//make sure that the template is there
	if( typeof newContent !== 'string' || newContent.length === 0 ) return '<p class="error">Form Template malformation. Please try your request again.</p>';
	//return if we don't have a unique handle for the field set
	if( typeof form_data.field_group !== 'string' || form_data.field_group === '' ) return '<p class="error">Need unique identifier for field set.</p>';
  //see if we have unique identifiers for the builder
  switch( form_data.query_type ) {
    case "rosters_teams":
    case "rosters_teams_admin":
      //if the event id is zero hide the level selector
      if( typeof form_data.contributions !== 'undefined' && form_data.contributions.event_id == 0 ) form_data.contributions.division_selector_options_hide = 'hide';
      if( form_data.query_type === form_data.field_group ) {
        //if there are multiple ids add the hook for the loop to use
        form_data.field_group = ( $.isArray( form_data.id ) ) ? form_data.field_group + '_{{field-set-id}}' : form_data.field_group;
        break;
      }
    default:
      //add the hook for the loop to use
      form_data.field_group = ( $.isArray( form_data.id ) ) ? form_data.field_group + '_{{id}}' : form_data.field_group;
  }
  //if we are going to have to manage sub items, add a hook for the parent id
  if( form_data.go_flat && $.isArray( form_data.id ) ) {
    //if the go_flat isn't set then add the id hook
    if( form_data.go_flat === form_data.query_type ) form_data.go_flat = form_data.field_group;
  } else {
    form_data.go_flat = '{{field-set-id}}';
  }
  //if we have a base id, use that
  newContent = newContent.replace(new RegExp('{{field-set-id-flat}}', 'g'), ( form_data.go_flat ) ? form_data.go_flat : form_data.field_group);
  //add unique identifier to new form template
  newContent = newContent.replace(new RegExp('{{field-set-id}}', 'g'), form_data.field_group);
  //if we need to go flat switch the form-id
	if( typeof form_data.field_origin_id !== 'undefined' ) newContent = newContent.replace(new RegExp('{{field-set-id-origin}}', 'g'), form_data.field_origin_id);
  //if we need to add a locker target, do so
	if( typeof form_data.dropdown_target !== 'undefined' ) newContent = newContent.replace(new RegExp('{{dropdown_target}}', 'g'), form_data.dropdown_target);
// 	//find all variables that we need to replace
// 	var contentVars = newContent.match(/{{(.+?)}}/g);
// 	//call back function to make eliminate duplicates
// 	contentVars = contentVars.filter( function (value, index, self) { return self.indexOf(value) === index; } );
  //if we don't have an id, get a new form if possible
  var form_new_or_get = ( form_data.id === null ) ? true : false;
  //if there are any global form presets add them now
  if( typeof g365_form_data.form[form_data.query_type]['form_defaults'] !== 'undefined' ) $.each( g365_form_data.form[form_data.query_type]['form_defaults'], function(preset_name, preset_val){ newContent = newContent.replace('{{' + preset_name + '}}', preset_val); });
  //these types of forms can take additional build conditions, so change the flag so we pull data from the server
  if(
    (form_data.query_type === 'pl_ev' || 
     form_data.query_type === 'player_event' || 
     form_data.query_type === 'pl_cert' || 
     form_data.query_type === 'pl_cert_sl' || 
     form_data.query_type === 'camps' || 
     form_data.query_type === 'passport' || 
     form_data.query_type === 'rosters_teams_admin' 
    ) && form_data.contributions !== null ) form_new_or_get = false;
  //switch for getting data from the server or just using the data generated from the init form
  if( form_new_or_get ) {
    //do a preliminary integration with the contributions
    if( form_data.contributions !== null ) $.each( form_data.contributions, function(cont_name, cont_val){
      //if it's a special data type process further
      if( cont_val !== null && cont_val.toString().match(/^(\[|\{)/) ) {
        cont_val = g365_sub_template_parser(cont_name, form_data.id, cont_val, form_data);
      }
      newContent = newContent.replace(new RegExp('{{' + cont_name + '}}', 'g'), ((cont_val !== null) ? cont_val.toString().trim() : ''));
    });
		//regex replace all template variables
		defObj.resolve(newContent.replace(/{{(.+?)}}/g, ''));
	} else {
    var g365_get_data = false;
    //see if we already have all the data
    $.each(form_data.id, function( dex, val ){ if( typeof g365_form_data[form_data.query_type][val] === 'undefined' ) g365_get_data = true; });
    if( ( form_data.query_type === 'rosters_teams_admin' ) && form_data.contributions !== null ) g365_get_data = true;
    if( g365_get_data === true ) {
      //use the variables from the template to make a query and get the players existing data
      var data_set = {};
      data_set[form_data.query_type] = {
        proc_type : 'get_data',
        ids : form_data.id,
        contributions : form_data.contributions
      }
      //added for rosters_teams_admin to substitue for the id
      if( ( form_data.query_type === 'rosters_teams_admin' ) && form_data.id === null ) {
        data_set[form_data.query_type].field_group = form_data.field_group;
//         form_data.id = form_data.field_group;
      }
      
      g365_serv_con( data_set )
      .done( function(response) {
        console.log('build template response', response);
        if (response.status === 'success') {
          $.each(response.message, function(data_type, data_set){
            //set the base object if we don't already have one
            if( typeof g365_form_data[data_type] == 'undefined' ) g365_form_data[data_type] = {};
            //loop through local global variables replacing each with data from the query
            $.each(data_set, function(data_id, data_arr){
              //set the global object if it isn't set already
              if( typeof g365_form_data[data_type][data_id] == 'undefined' ) g365_form_data[data_type][data_id] = {};
              //log the string if we have a string, probably means error
              if( typeof data_arr === 'string' ) {
                g365_form_data[data_type][data_id] = data_arr;
                return;
              }
              //add each key value pair to the global set
              $.each(data_arr, function(data_key, data_val){
                g365_form_data[data_type][data_id][data_key] = data_val;
                //added for rosters_teams_admin to substitue for the id
                if( ( form_data.query_type === 'rosters_teams_admin' ) && data_key === 'player_rosters' ) form_data.contributions.player_rosters = data_val;
              });
            });
          });
          //now that we have the whole data set, compile the forms
          newContent = g365_build_template_from_data_proc(newContent, form_data.id);
        } else {
          console.log('false');
          newContent = response.message;
        }
      })
      .fail( function(response) {
        console.log('error', response);
        newContent = response.responseText;
      })
      .always( function(response) {
        if( typeof newContent === 'object' ) {
          $.each(newContent, function(data_string, data_content){
            if( data_content === null ) return;
            newContent[data_string] = data_content.replace(/{{(.+?)}}/g, '');
          });
          //if we don't have any disabled entries, just simplify the response
          if( Object.keys(newContent).length == 2 && newContent.disabled === null ) newContent = newContent.enabled;
          defObj.resolve(newContent);
        } else {
          defObj.resolve(newContent.replace(/{{(.+?)}}/g, ''));
        }
      });
    } else {
      //if we already had all the form data, put together the form.
      newContent = g365_build_template_from_data_proc(newContent, form_data.id);
      if( typeof newContent === 'object' ) {
        $.each(newContent, function(data_string, data_content){
          if( data_content === null ) return;
          newContent[data_string] = data_content.replace(/{{(.+?)}}/g, '');
        });
        if( Object.keys(newContent).length == 2 && newContent.disabled === null ) newContent = newContent.enabled;
        defObj.resolve(newContent);
      } else {
        defObj.resolve(newContent.replace(/{{(.+?)}}/g, ''));
      }
    }
  }
  return defObj.promise();
}

//handle form submits
function g365_handle_form_submit( form_event ){
	console.log('sge', form_event);
  form_event.preventDefault();
  var submitted_form = $(this);

	if( submitted_form.attr("action") ) {
		console.log('hee');
		submitted_form.submit();
	}
	var target_form = $(this);
  $( "input[name='nds-pmd']", target_form ).remove();
  var field_type = target_form.attr('data-g365_type');
  if( typeof field_type === 'undefined' ) return '<p>Bad type.</p>';
  var target_form_id = target_form.attr('id');
	var newContent = '';
	var data_set = {};
  var locker = $("#" + target_form_id + "_wrap select[data-g365_additional_lock='" + target_form_id + "_data']");
  var lockers = locker.children('option');
  var lock_incomplete = false;
  submitted_form.addClass('submit_shield');
  if(lockers.length !== 0) lockers.each(function(){ var lock = $(this); if(lock.val() !== '' && lock.prop('disabled') !== true ) lock_incomplete = true; });
  if( lock_incomplete ) {
    var locker_parent = locker.parent();
    var locker_closest = locker.closest(':visible');
    locker_parent.children('.error').remove();
    locker_closest.children('.error').remove();
    if( locker.is(':visible') ) {
      locker_parent.append('<p id="' + (target_form_id + '_error_message') + '" class="error">Must use all entries.</p>');
      locker_parent.scrollView();
    } else {
      locker_closest.prepend('<p id="' + (target_form_id + '_error_message') + '" class="error">This section needs to be filled out.</p>')
      locker_closest.scrollView();
    }
    submitted_form.removeClass('submit_shield');
    return;
  }
  
  //if we are going to include a var about the button clicked - used for admin claiming form
  var temp_mods = '';
  if( document.activeElement.hasAttribute('data-g365_sender') ) temp_mods = $( '<input type="hidden" name="' + document.activeElement.getAttribute('name') + '" value="' + document.activeElement.getAttribute('data-g365_sender') + '">' ).prependTo(target_form);

  data_set[field_type] = {
		proc_type : 'proc_data',
		form_data : target_form.serialize()
	}
	if( submitted_form.attr("action") ) { submitted_form.submit();
		console.log('ggege', data_set);
		window.location.reload( submitted_form.attr("data-href") );
	}

  
  if( temp_mods !== '' ) temp_mods.remove();
  
  //keep track of error status
  var section_errors = false;
  //keep track of successful record ids
  var success_record_ids = [];
  var success_record_add = [];
  //try to submit form
  var temp2 = [];
  $.each(data_set[field_type].form_data.replace(new RegExp('%5B', 'g'), '[' ).replace(new RegExp('%5D', 'g'), ']' ).split('&'), function(dex, data){ data = data.split('='); temp2[data[0]] = data[1]; });
	g365_serv_con( data_set )
	.done( function(response) {
    console.log(response);
    if (typeof response.message === 'object') {
      //if we need a global setting for a success message
      //if (response.status === 'success') {}
      //loop through template variables replacing each with data from the query
      $.each(response.message, function(data_type, data_results) {
        var result_template = g365_form_data.form[data_type].form_template_result;
        //make sure that the template is there
        if( typeof result_template !== 'string' || result_template.length === 0 ) {
          newContent += '<p class="error">Form Result Template malformation for ' + data_type + '. Data write successful.</p>';
          section_errors = true;
          submitted_form.removeClass('submit_shield');    
          return;
        }
        //check for result data
        if( typeof data_results !== 'object' || $.isEmptyObject(data_results) ) {
          newContent += '<pclass="error">No results returned ' + data_type + '.</p>';
          section_errors = true;
          submitted_form.removeClass('submit_shield');    
          return;
        }
        //build the result block and update our object
        newContent += '<div><ul>';
        $.each(data_results, function(data_id, data_result){
          //set the class for the result message;
          var li_class;
          if(/success/i.test(data_result.message)) {
            //if we are successfull and need a redirect, please
            if( typeof data_result.redirect === 'string' && data_result.redirect !== '' ) {
              if( data_result.redirect === 'reload' ) {
                window.location.reload();
              } else {
                window.location.href=data_result.redirect;
              }
            }
            li_class = 'success';
            if( typeof data_result.wrapper_id !== 'undefined' ) {
              $('#' + data_result.wrapper_id).removeClass('error_wrapper');
              $('#' + data_result.wrapper_id + '_id' ).val(data_result.id);
            }
            success_record_ids[success_record_ids.length] = data_result.id;
            if( typeof data_result.passport !== 'undefined' ) success_record_add[data_result.id] = data_result.passport;
          } else {
            $('#' + data_id).addClass('error_wrapper');
            li_class = 'error';
            section_errors = true;
          }
          //if we have a proper id, feel free to update our internal data
          if( !isNaN(parseInt(data_id)) ) {
            if( typeof g365_form_data[data_type] == 'undefined' ) g365_form_data[data_type] = {};
            if( typeof g365_form_data[data_type][data_id] == 'undefined' ) {
              g365_form_data[data_type][data_id] = {};
              //also add the id to the form incase the user submits again.
              $('#' + data_result.wrapper_id + '_id').val(data_id);
            }
            $.each(data_result, function(data_key, data_val){
              g365_form_data[data_type][data_id][data_key] = data_val;
            });
            //process the result, error or success
            newContent += result_template.replace(new RegExp('{{li_class}}', 'g'), li_class ).replace(new RegExp('{{result_title}}', 'g'), g365_form_data[data_type][data_id].element_title).replace(new RegExp('{{result_status}}', 'g'), '<br>' + data_result.message);
          } else {
            //set the target
            //process the result, error or success
            newContent += result_template.replace(new RegExp('{{li_class}}', 'g'), li_class ).replace(new RegExp('{{result_title}}', 'g'), data_result.element_title).replace(new RegExp('{{result_status}}', 'g'), '<br>' + data_result.message);
          }
        });
        newContent += '</ul></p>';
        //if we are a nested form, push our results to the appropriate orgin fields and wrap up the nested form
        //we execute this after the above each block to get the form data object updated before we exit
        var origin_form_element_id = target_form.attr('data-target_field');
        var origin_form_element = $('#' + origin_form_element_id);
        if( origin_form_element.length ){
          //only evaluate the first entry, we only have one reference to pass data back to.
          var response_reference = data_results[$.map(data_results, function(value, key){return key;})[0]];
          //if we have success
          if( typeof response_reference.id !== 'undefined' ) {
            //then take the action of the origin field
            var origin_action = origin_form_element.attr( 'data-g365_action' );
            if( typeof origin_action !== 'undefined' ){
              switch( origin_action ) {
                case 'load_form':
                  //set the field to pull from
                  origin_form_element.val(response_reference.id).trigger('ajaxlivesearch:hide_result');
                  //build the form or line item
                  g365_build_form_from_data( origin_form_element );
                  break;
                case 'set_select':
                  //create and set the select drop down 
                  origin_form_element.append('<option value="' + response_reference.id + '" data-g365_short_name="' + (( typeof response_reference.name === 'undefined' ) ? '' : response_reference.name) + '">' + response_reference.element_title + '</option>');
                  origin_form_element.val(response_reference.id).change();
                  break;
                case 'select_data':
                  //set the vanity field for a livesearch
                  origin_form_element.val(response_reference.element_title).trigger('ajaxlivesearch:hide_result');
                  //set the proper target with the new id
                  var origin_form_element_target = $('#' + origin_form_element.attr('data-ls_target'));
                  origin_form_element_target.attr('data-g365_short_name', response_reference.element_title);
                  origin_form_element_target.attr('data-g365_additional_data', response_reference.level);
                  origin_form_element_target.val(response_reference.id).change();

                  //incase we have multi-level conditional
                  g365_manage_add_button( origin_form_element_target );
                  // if this is a select_data action with a click at the end, perform the click action upon successful data add
                  if( !section_errors && response_reference.id !== '' && typeof origin_form_element.attr('data-g365_select_click') === 'string' ) {
                    $('#' + origin_form_element.attr('data-g365_select_click')).attr('data-g365_form_load_min', 'true-false').click();
                  }
                  break;
                case 'add_result':
                  $('<div class="form_message">' + newContent + '</div>').insertBefore(origin_form_element);
                  break;
              }
            }
            //open the origin field parent or element itself if it's a select
            if( origin_form_element.is('select') ) {
              origin_form_element.slideDown();
            } else if( origin_form_element.is('div') ) {
              origin_form_element.removeClass( 'hide' );
            } else {
              origin_form_element.parent().slideDown();
            }
            //delete the form_event
            target_form.parent().fadeOut(400, 'swing', function(){
              
              target_form.parent().remove();
              //scroll to the target form field
              origin_form_element.scrollView();
            });
            submitted_form.removeClass('submit_shield');    
            return;
          }
        } else {
          g365_form_message_clear( target_form );
        }
      });
		} else {
			console.log('false');
			newContent = '<p>' + response.message + '</p>';
		}
	})
	.fail( function(response) {
		console.log('error', response);
		newContent = '<p>' + response.responseText + '</p>';
	})
	.always( function(response) {
    var message_wrap = target_form.attr('id');
    if( message_wrap.endsWith('_fieldset') ) message_wrap = message_wrap.substring(0, message_wrap.length-9);
		var message_wrapper = $('#' + message_wrap + '_message', target_form);
    if( message_wrapper.length ) message_wrapper.html(newContent).removeClass('hide').trigger('result_complete', { 'error_status': section_errors, 'result_ids': success_record_ids, 'result_add': success_record_add });
    if( section_errors ) {
      message_wrapper.scrollView();
      submitted_form.removeClass('submit_shield');    
      return 'return_error';
    }
    submitted_form.removeClass('submit_shield');
  	return 'return_success';
	});
  return 'whaaaaa...';
}

//temp function to force rebuild, please remove if you are seeing this.
function non_sense(){
  console.log('none');
  var tensor = 'hello';
  return tensor;
}

function g365_field_format_lock( target, lock_type ) {
  if( target instanceof jQuery ) {
    var target_val = target.val();
    var target_val_new = '';
    if( target_val === '' ) return;
    switch(lock_type) {
      case 'tel':
//         // Remove non-numeric characters
//         var digits = target_val.replace(/\D/g, '');

//         // Extract country code if present
//         var countryCode = '';
//         if (digits.length > 10) {
//             // Determine country code length
//             var countryCodeLength = digits.length - 10;
//             countryCode = digits.substr(0, countryCodeLength);
//             digits = digits.substr(countryCodeLength);
//         }

//         // Ensure there are exactly 10 digits left for the phone number
//         digits = digits.substr(0, 10);

//         // Format the phone number
//         var formatted_val = '';
//         if (countryCode !== '') {
//             formatted_val = countryCode + '-';
//         }

//         if (digits.length > 3) {
//             formatted_val += digits.substr(0, 3) + '-';
//             if (digits.length > 6) {
//                 formatted_val += digits.substr(3, 3) + '-';
//                 formatted_val += digits.substr(6, 4);
//             } else {
//                 formatted_val += digits.substr(3);
//             }
//         } else {
//             formatted_val += digits;
//         }
        target_val = target_val.replace(/[^\d]/g,'').substring(0,10);
        target_val_new = target_val.substr(0, 3);
        if( target_val.length >= 4 ) target_val_new += '-' + target_val.substr(3, 3);
        if( target_val.length >= 7 ) target_val_new += '-' + target_val.substr(6);
        break;
      case 'date':
        var current_d = new Date();
        target_val = target_val.replace(/[^\d]/g,'').substring(0,8);
        target_val_new = ((parseInt(target_val.substr(0, 2)) > 12) ? '12' : target_val.substr(0, 2));
        if( target_val.length >= 3 ) target_val_new += '-' + ((parseInt(target_val.substr(2, 2)) > 31) ? '31' : target_val.substr(2, 2));
        if( target_val.length >= 5 ) target_val_new += '-' + ((parseInt(target_val.substr(4)) > current_d.getFullYear()) ? current_d.getFullYear() : target_val.substr(4));
        break;
    }
    target.val( target_val_new );
  }
}

//attach to input, controls select elements, (handles division limiting (gteq), future support for lteq, gt, lt)
function g365_addition_limiter( limiter ){
  var to_limit = $('#' + limiter.attr('data-g365_additional_target_limit'));
  var the_limit = limiter.attr('data-g365_additional_data');
  if( to_limit.length !== 1 ) return;
  if( typeof the_limit === 'undefined' ) the_limit = null;
  //loop through and release or disable
  $('option', to_limit).each(function(){
    var this_option = $(this);
    this_option.prop('disabled', false);
    if( parseInt(the_limit) > parseInt(this_option.val()) ) this_option.prop('disabled', true);
  });
  //if we have a link, then the field is hidden and we need to select the
  if( limiter.attr('data-g365_link_target') === 'hide' ) {
    to_limit.val( (the_limit === null) ? '' : the_limit );
    g365_manage_add_button( to_limit );
  }
}

//update and validate the forms dataset at the top and in the fieldset sections
function g365_manage_cart_form( target, type, items ) {
  //build/rebuild the drop down
  switch( type ) {
    case 'rosters_event':
    case 'rosters_teams':
    case 'rosters':
      //see if we have existing fieldsets to manage
      var secondary_check_target = $('#' + target.attr('data-g365_additional_lock'));
      var secondary_ele_list = {};
      var secondary_ele_list_unassigned = {};
      //check the already created options to see if we need to call out a fieldset.
      if( secondary_check_target.length === 1 ) secondary_check_target.children('div').each(function(dex){
        //evaluate all elements to make sure the we have everything accounted for, or mark it and disable items
        var secondary_target = $(this);
        var secondary_target_key = secondary_target.attr('data-g365_dropdown_key');
        //if we don't have a target set, set them here.
        if( secondary_target.attr('data-g365_dropdown_target') !== target.attr('id') ) secondary_target.attr('data-g365_dropdown_target', 'event_id');
        //if we don't have a key set, set them here.
        if( typeof secondary_target_key === 'undefined' || secondary_target_key === '' ) {
          //create a temp key for the entry
          var temp_secondary_target_key = $('#' + secondary_target.attr('id') + '_event_id').attr('data-g365_short_name') + ' | un' + dex;
          //try to assign the target and key
          secondary_target.attr('data-g365_dropdown_key', temp_secondary_target_key);
          secondary_target_key = temp_secondary_target_key;
        }
        secondary_ele_list[secondary_target_key] = {origin: secondary_target, key: secondary_target_key};
      });
      //start to create the event drop down
      target.html('');
//       target.append('<option value=""> -- Please Select</option>');
      var option_titles = {};
      //figure out what we got, loop through the provided dataset starting with top level product
      $.each(items, function(id_key, event_data){
        //then loop through the product versions, specially organized this way to support product variations
        $.each(event_data.vars, function(var_id_key, var_data){
          //if we don't have a linked event get out; we can't lock the form without that info
          if( var_data.event_id === 0 ) return;
          //we need a line item for every quantity
          for (i = 1; i <= var_data.qty; i++) {
            //if an event has divisions, add it on so we can get the options later
            var_data.event_divisions = ( var_data.event_divisions === 0 ) ? '' : var_data.event_divisions;
            //if we only have one line item, don't call it out, otherwise add a unique count to differentiate
            var count = ' | Team ' + i;
            //concat the option title and add it to a list
            var option_title = var_data.full_name + count;
            //if we have a match with a deployed fieldset disable this option, and remove it from the object so we can do some reconciliation with any left overs
            var deployed_set = '';
            if( typeof secondary_ele_list[option_title] !== 'undefined' ) {
              deployed_set = ' disabled';
              secondary_ele_list[option_title].origin.children('div').removeClass('form-disabled').prop('disabled', false);
              delete secondary_ele_list[option_title]; 
            }
            //write all options that we created
            var this_option = $( '<option value="' + var_data.event_id + '" data-g365_additional_data=\'' + ((typeof var_data.event_divisions === 'string') ? '' : JSON.stringify(var_data.event_divisions) ) + '\'' + deployed_set + '>' + option_title + '</option>' ).appendTo(target);
            //compile the option that haven't been used
            if( deployed_set === '' ) option_titles[option_title] = {origin: this_option, key: option_title, type: var_data.full_name, count: count};
          }
        });
      });
      //loop through any leftover fieldsets that don't have a designation and try to assign them
      $.each(secondary_ele_list, function(key, val){
        //extract the base key
        var base_key = key.substr(0, key.indexOf(' | '));
        //keep track of it's assignment status
        var unassigned = true;
        //see if we have any others to fall back on
        $.each(option_titles, function(option_key, option_vals){
          if( base_key === option_vals.type ) {
            //disable the new selection
            option_vals.origin.prop('disabled', true);
            //change the underlaying event short name
            $('#' + val.origin.attr('id') + '_' + option_vals.origin.parent().attr('id')  ).attr( 'data-g365_short_name', option_key ).change();
            //change the parent data reference
            val.origin.attr('data-g365_dropdown_key', option_key);
            //call title updaters, first inner form, then collapsed
            g365_form_section_expand_collapse( $('#' + val.origin.attr('id') + '_fieldset .change-title.g365-expand-collapse-fieldset'), 'close' );
            //delete this entry
            delete secondary_ele_list[key];
            //delete from the available options too
            delete option_titles[option_key];
            //set the assigment status so this fieldset doesn't get disabled
            unassigned = false;
            //since we have a match, exit this loop
            return false;
          }
        });
        //if we didn't get assigned, disable the fieldset and continue looping
        if( unassigned ) {
          $('.change-title.g365-expand-collapse-fieldset' , val.origin).trigger('click', 'closed');
          val.origin.children('div').addClass('form-disabled').prop('disabled', true);
          return;
        }
        val.origin.children('div').removeClass('form-disabled').prop('disabled', false);
      });
      var form_holder = target.closest('.form-holder').children(':not(.primary-form)');
      if( target.children('option:enabled:not([value=""])').length === 0 ) {
        form_holder.addClass('hide');
      } else if ( form_holder.first().hasClass('hide') ) {
        form_holder.removeClass('hide');
      }
      
      target.change();
      break;
    case 'pl_ev':
    case 'camps':
    case 'passport':
    case 'club_team':
      //set global var
      var event_pointer = null;
      switch(type) {
        case 'pl_ev':
          event_pointer = 'event_id_pm';
          break;
        case 'camps':
        case 'passport':
          event_pointer = 'event_id_cp';
          break;
        case 'club_team':
          event_pointer = 'event_id_ct';
          break;
      }
      //see if we have existing fieldsets to manage
      var secondary_check_target = $('#' + target.attr('data-g365_additional_lock'));
      var secondary_ele_list = {};
      var secondary_ele_list_unassigned = {};
      //check the already created options to see if we need to call out a fieldset.
      if( secondary_check_target.length === 1 ) secondary_check_target.children('div').each(function(dex){
        //evaluate all elements to make sure the we have everything accounted for, or mark it and disable items
        var secondary_target = $(this);
        var secondary_target_key = secondary_target.attr('data-g365_dropdown_key');
        //if we don't have a target set, set them here.
        if( secondary_target.attr('data-g365_dropdown_target') !== target.attr('id') ) secondary_target.attr('data-g365_dropdown_target', 'event_id_pm');
        //if we don't have a key set, set them here.
        if( typeof secondary_target_key === 'undefined' || secondary_target_key === '' ) {
          //create a temp key for the entry
          var temp_secondary_target_key = $('#' + secondary_target.attr('id') + '_' + event_pointer).attr('data-g365_short_name') + ' | un' + dex;
          //try to assign the target and key
          secondary_target.attr('data-g365_dropdown_key', temp_secondary_target_key);
          secondary_target_key = temp_secondary_target_key;
        }
        secondary_ele_list[secondary_target_key] = {origin: secondary_target, key: secondary_target_key};
      });
      //start to create the event drop down
      target.html('');
//       target.append('<option value=""> -- Please Select</option>');
      var option_titles = {};
      //figure out what we got, loop through the provided dataset starting with top level product
      $.each(items, function(id_key, event_data){
        //then loop through the product versions, specially organized this way to support product variations
        $.each(event_data.vars, function(var_id_key, var_data){
          //if we don't have a linked event get out; we can't lock the form without that info
          if( var_data[event_pointer] === 'undefined' || var_data[event_pointer] === 0) return;
          //we need a line item for every quantity
          for (i = 1; i <= var_data.qty; i++) {
            //if we only have one line item, don't call it out, otherwise add a unique count to differentiate
            var count = ' | Player ' + i;
            //concat the option title and add it to a list
            var option_title = var_data.full_name + count;
            //if we have a match with a deployed fieldset disable this option, and remove it from the object so we can do some reconciliation with any left overs
            var deployed_set = '';
            if( typeof secondary_ele_list[option_title] !== 'undefined' ) {
              deployed_set = ' disabled';
              secondary_ele_list[option_title].origin.children('div').removeClass('form-disabled').prop('disabled', false);
              delete secondary_ele_list[option_title];
            }
            //write all options that we created
            var this_option = $( '<option value="' + var_data[event_pointer] + '"' + deployed_set + '>' + option_title + '</option>' ).appendTo(target);
            //compile the option that haven't been used
            if( deployed_set === '' ) option_titles[option_title] = {origin: this_option, key: option_title, type: var_data.full_name, count: count};
          }
        });
      });
      //loop through any leftover fieldsets that don't have a designation and try to assign them
      $.each(secondary_ele_list, function(key, val){
        //extract the base key
        var base_key = key.substr(0, key.indexOf(' | '));
        //keep track of it's assignment status
        var unassigned = true;
        //see if we have any others to fall back on
        $.each(option_titles, function(option_key, option_vals){
          if( base_key === option_vals.type ) {
            //disable the new selection
            option_vals.origin.prop('disabled', true);
            //change the underlaying event short name // should be equal to event_pointer: option_vals.origin.parent().attr('id');
            $('#' + val.origin.attr('id') + '_' + event_pointer  ).attr( 'data-g365_short_name', option_key ).change();
            //change the parent data reference
            val.origin.attr('data-g365_dropdown_key', option_key);
            //call title updaters, first inner form, then collapsed
            g365_form_section_expand_collapse( $('#' + val.origin.attr('id') + '_fieldset .change-title.g365-expand-collapse-fieldset'), 'close' );
            //delete this entry
            delete secondary_ele_list[key];
            //delete from the available options too
            delete option_titles[option_key];
            //set the assigment status so this fieldset doesn't get disabled
            unassigned = false;
            //since we have a match, exit this loop
            return false;
          }
        });
        //if we didn't get assigned, disable the fieldset and continue looping
        if( unassigned ) {
          $('.change-title.g365-expand-collapse-fieldset' , val.origin).trigger('click', 'closed');
          val.origin.children('div').addClass('form-disabled').prop('disabled', true);
          return;
        }
        val.origin.children('div').removeClass('form-disabled').prop('disabled', false);
      });
      var form_holder = target.closest('.form-holder').children(':not(.primary-form)');
      if( target.children('option:enabled:not([value=""])').length === 0 ) {
        form_holder.addClass('hide');
      } else if ( form_holder.first().hasClass('hide') ) {
        form_holder.removeClass('hide');
      }
      target.change();
      break;
  }
  return;
}

//form init
function g365_form_start( target ) {
  //get types from the element
  var data_set = {};
  //function for processing the request data tree
  function g365_proc_start_data(e){
    //need the type var to send
    if( e === '' || e === null || typeof e === 'undefined' ) {
      console.log( 'Need proper form type variable.' );
      return false;
    }
    //if we have ids on the element, include them
    e = e.split(':::');
    //place we are writing ids
    var j = 'ids';
    e.forEach(function(el){
      //if its the first item, that's the type and we need to add it to the request
      if( el == e[0] ) {
        //if this is a preset, we need to add it differently
        if( el.indexOf('_preset') !== -1 ) {
          e[0] = el.substring(0, el.length-7);
          j = 'preset';
        }
        //if the type already exist don't create it again
        if ( typeof data_set[e[0]] === 'undefined' ) data_set[e[0]] = {
          proc_type: 'get_form',
          ids: [],
          preset: []
        };
        //continue the looping
        return;
      }
      data_set[e[0]][j].push(el);
    });
  }
  //function to write responses
  function g365_inner_form_start( error_message ) {
    function g365_inner_wrap_up( current_form ) {
      var current_form_eval = $('.form-holder', current_form);
      if( current_form_eval.length === 1 ) current_form = current_form_eval;
      g365_form_start_up( current_form );
      //after start-up considerations
      //if we need to start minimized
      if( current_form.parent().attr('data-g365_form_load_min') === 'true' ) current_form.find('.form-collapse-title.g365-expand-collapse-fieldset').click();
      //if we need to close up an init form on load 
      var toggle_target = $('a[data-g365_toggle_parent]', current_form);
      var load_target = $('#' + toggle_target.attr('data-g365_load_target') + '_data>*');
      if( toggle_target.length && load_target.length ) {
        $( '#' + toggle_target.attr('data-g365_load_target') + '_submit' ).show();
        current_form.children(':not(.primary-form)').addClass('hide');
        if( toggle_target.attr('data-g365_toggle_parent') !== 'true' ) $( '#' + toggle_target.attr('data-g365_toggle_parent') ).removeClass('hide');
      }
      //extra form helpers if needed
      $('.g365_bulk_add', '#g365_bulk_add_wrap').click( g365_bulk_add );
    }

    //parse the errors
    if( typeof error_message !== 'undefined' ) g365_form_data.error = error_message;
    //set a wrapper if we don't have one
    if( typeof g365_form_data.wrapper === 'undefined' ) g365_form_data.wrapper = $("<div id='g365_form_wrap' class='g365_form_wrap'></div>").insertBefore(target);
    //ensure there are not errors
    if( g365_form_data.error === '' ) {
      $.each( data_set, function( key, value ) {
        //if we have the right data_type, process
        if( typeof g365_form_details.items !== 'undefined' ) {
          var no_locker_skip = true;
          $.each( g365_form_details.items, function(cat_id, cat_data){ if( cat_data.type === key && cat_data.no_init !== true ) no_locker_skip = false; } );
          if( no_locker_skip ) return;
        }
        if( typeof g365_form_data.form[key] !== 'undefined' ) {
          var presets = null;
          var this_form_template = g365_form_data.form[key].form_template_init;
          //set presets if we get them from the init pull
          if( typeof g365_form_data.form[key].form_defaults === 'object' ) presets = g365_form_data.form[key].form_defaults;
          //set presets from the init script
          if( value.preset.length > 0 ) {
            $.each(value.preset, function(dex, preset_pair){
              var preset = preset_pair.split('::');
              if( preset.length !== 2 ) return;
              if( presets === null ) presets = {};
              $.extend(presets, g365_form_data.form[key][ preset[0] + '_' + preset[1] ]);
            });
          }
          //start here
          //if we have presets, hit it
          if( typeof presets === 'object' ) $.each( presets, function(preset_name, preset_val){ this_form_template = this_form_template.replace(new RegExp('\{\{' + preset_name + '\}\}','g'), preset_val); });
          //regex replace all template variables if they aren't accounted for with presets
          this_form_template = this_form_template.replace(/{{(.+?)}}/g, '');
          //add the base form for the type
          var current_form = $( this_form_template ).appendTo( g365_form_data.wrapper );
          //add reference to proper cat_id
          var cat_id_ref;
          //see what data we have to integrate with the base form
          $.each( g365_form_details.items, function(cat_id, cat_data){
            //if we have the right data_type, process
            if( cat_data.type !== key ) return;
            //set the reference for later init
            cat_id_ref = cat_id;
            //if we have replacement title switch it
            if( cat_data.title !== '' ) {
              var h1s = $('h1.section_title', current_form);
              if( h1s.length ) {
                $('<h3 class="section_title">' + cat_data.title + '</h3>').insertBefore( h1s );
                h1s.remove();
              } else {
                $('<h3 class="section_title">' + cat_data.title + '</h3>').insertBefore( current_form.first() );
              }
            } 
            //if we have g365_form_details to initialize, build the dropdowns
            //specific to data type
            switch( cat_data.type ) {
              case 'rosters_event':
              case 'rosters_teams':
              case 'rosters_teams_admin':
              case 'rosters':
                //setup references
                g365_form_data.form[key].locker = $('#event_id', current_form);
                //if it changes run the updater
                if( g365_form_data.form[key].locker.is( 'select' ) ) {

                  g365_form_data.form[key].locker.change(function(){
                    //fieldset reference
                    var caller = $(this);
                    //get previous selection text to compare
                    var pre_event = caller.data('pre_event');
                    //set the new previous text
                    caller.data( 'pre_event', caller.children('option:selected').text().substr(0, caller.text().indexOf(' | ')) );
                    //if the main selection hasn't changed, stop updating.
                    if( pre_event === caller.data('pre_event') ) return;
                    //otherwise rebuild the additional data
                    g365_manage_additional_data( caller ); //check logic: onChange overwrite previous handler
                    //and show the next step
                    $('#team_level').parent().show();
                    //get the level part from the selector
                    var target_level = caller.children('option:selected').text().match(/\d\d?U/);
                    //if we don't have a target level, default to unselected
                    if( target_level === null ) {
                      $('#team_level').val('').change();
                    } else {
                      //if we have a target level, auto select the level dropdown
                      target_level = target_level[0].slice(0,-1);
                      target_level = parseInt(target_level);
                      //if we have a usable number select the level value
                      if( !isNaN(target_level) ) {
                        $('#team_level').val(target_level).change();
                        $('#team_level').parent().hide();
                      }
                    }
                  });
                } else {
                  //add additional data if we have it
                  if( typeof g365_form_data.form[key].locker.attr('data-g365_additional_data') !== 'undefined' && typeof g365_form_data.form[key].locker.attr('data-g365_additional_target') !== 'undefined' ) g365_form_data.form[key].locker.change(function(){ g365_manage_additional_data( $(this) ); }).change();
                }
                break;
              case 'pl_ev':
              case 'club_team':
              case 'camps':
              case 'passport':
                //setup references
                switch( cat_data.type ) {
                  case 'pl_ev':
                    g365_form_data.form[key].locker = $('#event_id_pm', current_form);
                    break;
                  case 'club_team':
                    g365_form_data.form[key].locker = $('#event_id_ct', current_form);
                    break;
                  case 'camps':
                  case 'passport':
                    g365_form_data.form[key].locker = $('#event_id_cp', current_form);
                    break;
                }
                //if it changes run the updater
                g365_form_data.form[key].locker.change(function(){
                  //fieldset reference
                  var caller = $(this);
                  //get previous selection text to compare
                  var pre_event = caller.data('pre_event');
                  //set the new previous text
                  caller.data( 'pre_event', caller.children('option:selected').text().substr(0, caller.text().indexOf(' | ')) );
                  //if the main selection hasn't changed, stop updating.
                  if( pre_event === caller.data('pre_event') ) return;
                });
                break;
            }
          });
          //add existing data if we have any
          if(value.ids.length) {
            //reorder ids if needed
            switch(key) {
              case 'rosters':
              case 'rosters_event':
              case 'rosters_teams':
              case 'rosters_teams_admin':
              case 'to_ed':
              case 'ro_ed':
                value.ids = value.ids.sort(function(a, b){return ((g365_form_data[key][a].level == g365_form_data[key][b].level) ? g365_form_data[key][a].division - g365_form_data[key][b].division : g365_form_data[key][a].level - g365_form_data[key][b].level)});
                break;
            }
            //the 'data-g365_dropdown_key' and the 'data-g365_dropdown_target' attributes need to be set for the locking (and subsequent roll up) to happen
            var form_data = {query_type: key, id: value.ids, field_group: key, go_flat: key};
            if( typeof g365_form_data.form[key].locker !== 'undefined' ) form_data.dropdown_target = g365_form_data.form[key].locker.attr('id');
            $.when(g365_build_template_from_data( form_data ))
            .done( function(form_template_message){
              //see what we got
              if( typeof form_template_message === 'object' ) {
                if( form_template_message.enabled === null && form_template_message.disabled === null ) {
                  $( '<p>No data found.</p>' ).prependTo( $('.form-holder>form>div:first-child', current_form) );
                } else {
                  if( form_template_message.disabled === null ) {
                    $('.form-holder>form>div:first-child', current_form).html($( form_template_message.enabled ));
                  } else if( form_template_message.enabled === null ) {
                    $('.form-holder>form>div:first-child', current_form).html($( form_template_message.disabled ));
                  } else {
                    //attach the new form for both disabled and enabled data points
                    var enabled_div = $('.g365_enabled_data', current_form);
                    if( enabled_div.length === 0 ) {
                      $( form_template_message.disabled ).prependTo( $('.form-holder>form>div:first-child', current_form) );
                      $( form_template_message.enabled ).prependTo( $('.form-holder>form>div:first-child', current_form) );
                    } else {
                      $( form_template_message.disabled ).prependTo( $('.form-holder>form>div:first-child .g365_disabled_data', current_form) );
                      $( form_template_message.enabled ).prependTo( $('.form-holder>form>div:first-child .g365_enabled_data', current_form) );
                    }
                  }
                }
              } else {
                //attach the new form
                $('.form-holder>form>div:first-child', current_form).html($( form_template_message ));
//                 $( form_template_message ).prependTo( $('.form-holder>form>div:first-child', current_form) );
              }
              //handle setup and/or locking
              if( typeof g365_form_data.form[key].locker !== 'undefined' && g365_form_data.form[key].locker.is('select') && typeof cat_id_ref !== 'undefined' ) g365_manage_cart_form(g365_form_data.form[key].locker, key, g365_form_details.items[cat_id_ref].items);
              //if this we want the admin functionality add it here
//               if( g365_form_details.g365_admin === "true" ) {
//                 var data_table = '';
//                 switch(key) {
//                   case 'rosters':
//                   case 'rosters_event':
//                   case 'rosters_teams':
//                     data_table += '<table class="widefat"><thead><tr><th>Division</th><th>Team Name</th><th>Pool Name</th><th>Pool Number</th><th>Team Restrictions</th><th>Time Restriction</th><th>Contact</th><th>Email</th><th>Event</th><th>Price</th></tr></thead><tbody>';
//                     $.each(value.ids, function(val_dex, val_id){
//                       data_table += '<tr><td>' + g365_grade_key[g365_form_data[key][val_id].level] + ((typeof g365_form_data[key][val_id].division !== 'undefined') ? ' ' + g365_form_data[key][val_id].division : '') + '</td><td>' + g365_form_data[key][val_id].org_name + ' ' + g365_form_data[key][val_id].level + 'U' + ((typeof g365_form_data[key][val_id].team_name !== 'undefined') ? ' ' + g365_form_data[key][val_id].team_name : '') + '</td><td></td><td></td><td></td><td></td><td>' + g365_form_details.g365_user_name + '</td><td>' + g365_form_details.g365_user_email + '</td><td>' + g365_form_data[key][val_id].event_name + '</td><td>' + '[insert price]' + '</td></tr>';
//                     });
//                     data_table += '</tbody></table>';
//                     break;
//                   case 'club_team':
//                   case 'camps':
//                     data_table += '<table class="widefat"><thead><tr><th>Player</th><th>Age</th><th>Birthdate</th><th>Grade</th><th>Grad Class</th><th>Jersey Size</th></tr></thead><tbody>'; 
//                     $.each(value.ids, function(val_dex, val_id){
//                       data_table += '<tr><td>' + g365_form_data[key][val_id].first_name + ' ' + g365_form_data[key][val_id].last_name + '</td><td>' + g365_form_data[key][val_id].age + '</td><td>' + g365_form_data[key][val_id].birthday + '</td><td>' + g365_form_data[key][val_id].grade + '</td><td>' + g365_form_data[key][val_id].grad_year + '</td><td>' + g365_form_data[key][val_id].jersey_size + '</td></tr>';
//                     });
//                     data_table += '</tbody></table>';
//                     break;
//                   case 'pl_ev':
//                     data_table += '<table class="widefat"><thead><tr><th>Player</th><th>Profile Image</th><th>Birthdate</th><th>Birth Certificate</th><th>Graduation Class</th><th>Report Card</th><th>First Verified</th><th>Verify Now</th></tr></thead><tbody>'; 
//                     $.each(value.ids, function(val_dex, val_id){
//                       data_table += '<tr><td>' + g365_grade_key[g365_form_data[key][val_id].level] + ((g365_form_data[key][val_id].division != '') ? ' ' + g365_form_data[key][val_id].division : '') + '</td><td>' + g365_form_data[key][val_id].org_name + ' ' + g365_form_data[key][val_id].level + 'U ' + ((g365_form_data[key][val_id].team_name != '') ? ' ' + g365_form_data[key][val_id].team_name : '') + '</td><td></td><td></td><td></td><td></td><td>' + g365_form_details.g365_user_name + '</td><td>' + g365_form_details.g365_user_email + '</td><td>' + g365_form_data[key][val_id].event_name + '</td><td>' + '[insert price]' + '</td></tr>';
//                     });
//                     data_table += '</tbody></table>';
//                     break;
//                 }
//                 current_form.prepend(data_table);
//               }
              g365_inner_wrap_up( current_form );
            });
          } else {
            //locking
            if( typeof g365_form_data.form[key].locker !== 'undefined' && g365_form_data.form[key].locker.is('select') && typeof cat_id_ref !== 'undefined' ) {
              g365_manage_cart_form(g365_form_data.form[key].locker, key, g365_form_details.items[cat_id_ref].items);
            }
            g365_inner_wrap_up( current_form );
          }
        }
      });
    } else {
      g365_form_data.wrapper.html( g365_form_data.error );
    }
    if( typeof error_message !== 'undefined' ) return false;
    return true;
  }
  
  //check that the processing flag is set
  if( g365_script_anchor === false ) return "Missing global connector.";
  //make sure that we have an element to put the form in
  if( typeof target === 'undefined' && !g365_script_element.hasAttribute('data-g365_type') ) return false;
  target = ( typeof target === 'undefined' || $('#' + target).length !== 1 ) ? $(g365_script_element) : $('#' + target);
  //get the form types
  var target_type = target.attr('data-g365_type');
  if( typeof target_type === 'undefined' || target_type === '' ) return false;
  //setup vars
  if( typeof g365_form_data.form === 'undefined' ) g365_form_data.form = {};
  //set/reset error status/code
  g365_form_data.error = '';
  //set global user key if available
  if( typeof g365_sess_data !== 'undefined' && typeof g365_form_details.admin_key !== 'undefined' ) g365_sess_data.admin_key = g365_form_details.admin_key;
  //if we have the info, process it
  target_type.split('|').forEach(g365_proc_start_data);
  //if we don't have any types get out
  if( Object.keys(data_set).length === 0 ) if( g365_inner_form_start("Error parsing form types.") === false ) return false;
  //get the init presets
  var target_preset = target.attr('data-g365_init_pre');
  //if we have init presets, add them
  if( typeof target_preset !== 'undefined' && target_preset !== '' ) target_preset.split('|').forEach(g365_proc_start_data);
  //add global settings if needed
  var settings = {};
  //style switch for external domains, styles never load on g365 domains
  if( g365_script_element.dataset.g365_styles == 'false' ) settings.styles = 'false';
  //settings default value
  if( Object.keys(settings).length === 0 ) settings = null;
  //get the form templates
  console.log('j', data_set);
  g365_serv_con( data_set, settings )
  .done( function(response) {
    console.log('p', response);
    if (response.status === 'success') {
      //loop through response and selectively add data to our tree
      $.each( response.message, function( type_key, type_values ) {
				if( typeof g365_form_data.form[type_key] === 'undefined' ) g365_form_data.form[type_key] = {};
				if( typeof g365_form_data[type_key] === 'undefined' ) g365_form_data[type_key] = {};
        $.each( type_values, function( values_key, value ) {
          if( $.isNumeric(values_key) ) {
            g365_form_data[type_key][values_key] = value;
          } else {
  	  			g365_form_data.form[type_key][values_key] = value;
          }
  			});
			});
      //possibly perform another check here
    } else {
      console.log('false', response);
      g365_form_data.error = response.message;
    }
  })
  .fail( function(response) {
    console.log('error', response);
    g365_form_data.error = response.responseText;
  })
  .always( function(response) {
    //start here to add the hook for after the form initializes
    if( g365_inner_form_start() && window.g365_func_wrapper.end.length > 0 ) window.g365_func_wrapper.end.forEach( function(func){ func.name.apply(null, func.args); });
    if( typeof response.style !== 'undefined' ) g365_form_data.wrapper.prepend( response.style );
  });
  return 'initalized.';
}
if( typeof g365_form_details !== 'undefined' ) window.g365_func_wrapper.sess[window.g365_func_wrapper.sess.length] = ( Object.keys(g365_form_details.items).length > 0 ) ?  {name : g365_form_start, args : [g365_form_details.wrapper_target]} : {name : g365_form_start, args : ['g365_form_options_anchor']};
$.fn.ajaxlivesearch = function (options) {
		/**
		 * Start validation
		 */
		if (options.loaded_at === undefined) {
				throw 'loaded_at must be defined';
		}

		if (options.token === undefined) {
				throw 'token must be defined';
		}
		/**
		 * Finish validation
		 */
		var ls = {
				url: options.target_url + "livesearch/",
				// This should be the same as the same parameter's value in config file
				form_anti_bot: "ajaxlivesearch_guard",
				cache: false,
				/**
				 * Beginning of classes
				 */
				form_anti_bot_class: "ls_anti_bot",
				footer_class: "ls_result_footer",
				next_page_class: "ls_next_page",
				previous_page_class: "ls_previous_page",
				page_limit: "page_limit",
				result_wrapper_class: "ls_result_div",
				result_class: "ls_result_main",
				container_class: "ls_container",
				pagination_class: "pagination",
				form_class: "search",
				loaded_at_class: "ls_page_loaded_at",
				token_class: "ls_token",
				current_page_hidden_class: "ls_current_page",
				current_page_lbl_class: "ls_current_page_lbl",
				last_page_lbl_class: "ls_last_page_lbl",
				total_page_lbl_class: "ls_last_page_lbl",
				page_range_class: "ls_items_per_page",
				page_ranges: [0, 5, 10],
				page_range_default: 5,
				navigation_class: "navigation",
				arrow_class: "ls-arrow",
				table_class: "",
				/**
				 * End of classes
				 */
				slide_speed: "fast",
				type_delay: 350,
				max_input: 20,
				min_chars_to_search: 0
		}

		ls = $.extend(ls, options);

		/**
		 * Remove footer, add border radius to bottom right and left
		 *
		 * @param footer
		 * @param result
		 */
		function remove_footer(footer, result) {
				footer.hide();
				// add border radius to the last row of the result
				result.find("table").addClass("border_radius");
		}

		/**
		 * Add footer, and remove border radius from bottom right and left
		 *
		 * @param footer
		 * @param result
		 */
		function show_footer(footer, result) {
				// add border radius to the last row of the result
				result.find("table").removeClass("border_radius");
				footer.show();
		}

		/**
		 * Return minimum value of
		 *
		 * @param page_range
		 */
		function get_minimum_option_value(page_range) {
				var minimumOptionValue;
				var i;
				var all_options = page_range.find('option');
				for (i = 0; i < all_options.length; i += 1) {
						if (minimumOptionValue === undefined && parseInt(all_options[i].value) !== 0) {
								minimumOptionValue = all_options[i].value;
						} else {
								if (parseInt(all_options[i].value) < parseInt(minimumOptionValue) && parseInt(all_options[i].value) !== 0) {
										minimumOptionValue = all_options[i].value;
								}
						}
				}

				return minimumOptionValue;
		}

		/**
		 * Return the relevant element of the form
		 *
		 * @param form
		 * @param key
		 * @param options
		 * @returns {*}
		 */
		function getFormInfo(form, key, options) {
				if (form === undefined || options === undefined) {
						throw 'Form or Options is missing';
				}

				var find = '.' + options.current_page_hidden_class;

				switch (key) {
						case 'result':
								return form.find('.' + options.result_wrapper_class);
						case 'footer':
								return form.find('.' + options.footer_class);
						case 'arrow':
								return form.find('.' + options.arrow_class);
						case 'navigation':
								return form.find('.' + options.navigation_class);
						case 'current_page':
								return form.find(find);
						case 'current_page_lbl':
								return form.find('.' + options.current_page_lbl_class);
						case 'total_page_lbl':
								return form.find('.' + options.total_page_lbl_class);
						case 'page_range':
								return form.find('.' + options.page_range_class);
						default:
								throw 'Key: ' + key + ' is not found';
				}
		}

		/**
		 * Slide up the result
		 *
		 * @param result
		 * @param options
		 */
		function hide_result(result, options) {
				result.slideUp(options.slide_speed);
		}

		/**
		 * Slide down the result
		 *
		 * @param result
		 * @param options
		 */
		function show_result(result, options) {
				result.slideDown(options.slide_speed);
		}

		/**
		 * Get the search input object (not just its value)
		 *
		 * @param search_object
		 * @param form
		 * @param options
		 * @param bypass_check_last_value
		 * @param reset_current_page
		 */
		function search(search_object, form, options, bypass_check_last_value, reset_current_page) {
				var result = getFormInfo(form, 'result', options);

				if ($.trim(search_object.value).length && $.trim(search_object.value).length >= options.min_chars_to_search) {
						/**
						 * If the previous value is different from the new one perform the search
						 * Otherwise ignore that. This is useful when user change cursor position on the search field
						 */
						if (bypass_check_last_value || search_object.latest_value !== search_object.value) {
								if (reset_current_page) {
										var current_page = getFormInfo(form, 'current_page', options);
										var current_page_lbl = getFormInfo(form, 'current_page_lbl', options);

										// Reset the current page (label and hidden input)
										current_page.val("1");
										current_page_lbl.html("1");
								}

								// Reset selected row if there is any
								search_object.selected_row = undefined;

								/*
								 If a search is in the queue to be executed while another one is coming,
								 prevent the last one
								 */
								if (search_object.to_be_executed) {
										clearTimeout(search_object.to_be_executed);
								}

								// Start search after the type delay
								search_object.to_be_executed = setTimeout(function () {
										// Sometimes requests with no search value get through, double check the length to avoid it
										if ($.trim(search_object.value).length) {
												// Display loading icon
												$(search_object).addClass('ajax_loader');

												var navigation = getFormInfo(form, 'navigation', options);
												var total_page_lbl = getFormInfo(form, 'total_page_lbl', options);
												var page_range = getFormInfo(form, 'page_range', options);
												var footer = getFormInfo(form, 'footer', options);

												var toPostData = $(form).serializeArray();
												var customData = $(search_object).data();

                        var kill_switch = false;
												$.each(customData, function(k, v){
                          if( k.substring(0,3) !== 'ls_' ) return;
                          if( k === 'ls_query_lock' ) {
                            var lock_set = v.split('|');
                            lock_set.forEach(function(lock_id){
                              var lock = $('#' + lock_id);
                              if( lock.length === 1 ) {
                                var lock_val = lock.val();
                                var lock_name = ( ((lock.attr('name')) ? lock.attr('name').match(/\[([^\[]+)\]$/) : null) || [0,lock.attr('data-g365_ls_lock')]);
                                if( typeof lock_val === 'undefined' || lock_val === '' || typeof lock_name[1] === 'undefined' ) {
                                  kill_switch = true;
                                  return false;
                                }
                                var dataObj = {};
                                dataObj['name'] = 'ls_query_lock[' + lock_name[1] + ']';
                                dataObj['value'] = lock_val;
                                toPostData.push(dataObj);
                              } else {
                                kill_switch = true;
                                return false;
                              }
                            });
                            if( kill_switch ) return false;
                          } else {
                            var dataObj = {};
                            dataObj['name'] = k;
                            dataObj['value'] = v;
                            toPostData.push(dataObj);
                          }
												});
                        if( kill_switch ) {
                          console.log('ls killed');
                          return false;
                        }
                        //clear any old messages
												$(search_object).siblings('.error, .success').remove();

												// Send the request
												$.ajax({
														type: "post",
														url: ls.url,
														headers: {'X-Requested-With': 'XMLHttpRequest'},
														data: toPostData,
														dataType: "json",
														cache: ls.cache,
														success: function (response) {
																if (response.status === 'success') {
																		var responseResultObj = $.parseJSON(response.result);

																		// set html result and total pages
																		result.find('table tbody').html(responseResultObj.html);

																		/*
																		 If the number of results is zero, hide the footer (pagination)
																		 also unbind click and select_result handler
																		 */
																		if (responseResultObj.number_of_results === 0) {
																				remove_footer(footer, result);
																		} else {
																				/*
																				 If total number of pages is 1 there is no point to have navigation / paging
																				 */
																				if (responseResultObj.total_pages > 1) {
																						navigation.show();
																						total_page_lbl.html(responseResultObj.total_pages);
																				} else {
																						// Hide paging
																						navigation.hide();
																				}

																				/**
																				 * Display select options based on the total number of results
																				 * There is no point to have a option with the value of 10 when there is
																				 * only 5 results
																				 */
																				//remove_select_options(responseResultObj.number_of_results, page_range, result, footer);

																				var minimumOptionValue = get_minimum_option_value(page_range);

																				// if is visible
																				if (footer.is(":visible")) {
																						// if number of results is less than minimum range except 0: Hide
																						if (parseInt(responseResultObj.number_of_results) <= parseInt(minimumOptionValue)) {
																								remove_footer(footer, result);
																						} else {
																								show_footer(footer, result);
																						}
																				} else {
																						// if number of results is NOT less than minimum range except 0: show
																						if (parseInt(responseResultObj.number_of_results) > parseInt(minimumOptionValue)) {
																								show_footer(footer, result);
																						} else {
																								remove_footer(footer, result);
																						}
																				}
																		}

																} else {
																		// There is an error
                                    if( response.message === 'Error: Token mismatch. Refresh the page. It seems that your session is expired.' ) location.reload();
																		result.find('table tbody').html(response.message);

																		remove_footer(footer, result);
																}

														},
														error: function (response) {
																console.log(response);
																result.find('table tbody').html('Something went wrong. Please modify your search.');

																remove_footer(footer, result);
														},
														complete: function (e) {
																/*
																 Because this is a asynchronous request
																 it may add result even after there is no query in the search field
																 */
																if ($.trim(search_object.value).length && result.is(":hidden")) {
																		show_result(result, options);
																}

																$(search_object).removeClass('ajax_loader');

																if (options.onAjaxComplete !== undefined) {
																		var data = {this: this};
																		options.onAjaxComplete(e, data);
																}
														}
												});
												// End of request
										}

								}, ls.type_delay);

						}
				} else {
						// If search field is empty, hide the result
						if (result.is(":visible") || result.is(":animated")) {
								hide_result(result, options);
						}
				}

				search_object.latest_value = search_object.value;
		}

		/**
		 * Generate Form html for the text input
		 *
		 * @param elem
		 * @param ls
		 * @returns {string}
		 */
		function generateFormHtml(elem, form_id, ls) {
				var elem_id = elem.attr('data-g365_type');
        var elem_add = (elem.attr('data-ls_no_add') === 'true') ? ' no-add-buttons' : '';

				elem.attr('autocomplete', 'off');
				elem.attr('name', 'ls_query');
				elem.addClass('ls_query');
				elem.attr('maxlength', ls.max_input);

				var optionsHtml = '', i, selected, option_value;
				var option_name = '';
				for (i = 0; i < ls.page_ranges.length; i += 1) {
						option_value = ls.page_ranges[i];
						if (option_value === 0) {
								option_name = 'All';
						} else {
								option_name = option_value;
						}

						if (ls.page_range_default === option_value) {
								selected = 'selected';
						} else {
								selected = '';
						}

						optionsHtml += '<option value="' + option_value + '" ' + selected + '>' + option_name + '</option>';
				}

				var paginationHtml = '<div class="grid-x ' + ls.footer_class + '">' +
								'<div class="cell small-12 ' + ls.page_limit + '">' +
                  '<select name="ls_items_per_page" class="' + ls.page_range_class + '">' + optionsHtml + '</select>' +
								'</div>' +
								'<div class="cell shrink ' + ls.navigation_class + '">' +
  								'<i class="left-arrow-button ' + ls.arrow_class + ' ' + ls.previous_page_class + '" tabindex="0">' + '</i>' +
								'</div>' +
								'<div class="cell shrink ' + ls.navigation_class + ' ' + ls.pagination_class + '">' +
                  '<label class="' + ls.current_page_lbl_class + '">1</label> / ' +
                  '<label class="' + ls.last_page_lbl_class + '"></label>' +
								'</div>' +
								'<div class="cell shrink ' + ls.navigation_class + '">' +
								  '<i class="right-arrow-button ' + ls.arrow_class + ' ' + ls.next_page_class + '" tabindex="0">' + '</i>' +
								'</div>' +
              '</div>';

				var wrapper = '<div class="' + ls.container_class + '">' +
								'<form accept-charset="UTF-8" class="' + ls.form_class + '" id="' + form_id + '" name="ls_form">' +
								'</form>' +
								'</div>';

				var hidden_inputs = '<input type="hidden" name="ls_anti_bot" class="' + ls.form_anti_bot_class + '" value="">' +
								'<input type="hidden" name="g365_session" value="' + ls.session + '">' +
								'<input type="hidden" name="g365_token" class="' + ls.token_class + '" value="' + ls.token + '">' +
								'<input type="hidden" name="g365_time" class="' + ls.loaded_at_class + '" value="' + ls.loaded_at + '">' +
								'<input type="hidden" name="ls_current_page" class="' + ls.current_page_hidden_class + '" value="1">' +
								'<input type="hidden" name="ls_query_id" value="' + elem_id + '">';

				var result = '<div class="' + ls.result_wrapper_class + elem_add + '" style="display: none;">' +
								'<div class="' + ls.result_class + '">' +
								'<table class="' + ls.table_class + '"><tbody></tbody></table>' +
								'</div>' + paginationHtml + '</div>';

				elem.wrap(wrapper);
				elem.before(hidden_inputs);
				elem.after(result);
		}

		//action functions
		var ls_action_functions = {
			navigate: function(e, data) {
				//the id of existing data or the kill command
				var form_selected_id = data.selected.attr('data-href');
        if( form_selected_id === "null" ) return;
        //check to see if we need additional data to make the url
        var form_selected_advance = data.selected.attr('data-href-adv');
        //if we have a hook and the corresponding url object to build with, please do
        if( form_selected_advance !== 'undefined' && $.isPlainObject(document.g365_herf_adv) ) {
          //split the hook so we know what to replace
          form_selected_advance = form_selected_advance.split('=');
          //the first part should be the same
          form_selected_id = document.g365_herf_adv.href;
          //loop through the vars and selectively replace with a new var
          $.each(document.g365_herf_adv.href_build, function(key, data){
            //if it's the key we are replacing, set it equal to the new var, otherwise use the previously set var
            form_selected_id += "&" + key + '=' + (( key === form_selected_advance[0] ) ? form_selected_advance[1] : data);
          });
        }
				// goto the target url
				window.location.href = form_selected_id;
	//         // set the input value
	//         this_input.val(selectedOne);
	//         // hide the result
	//         this_input.trigger('ajaxlivesearch:hide_result');
			},
			load_form: function(e, data) {
        //clear any old messages
        data.searchField.siblings('.error, .success').remove();
				//the id of existing data or the kill command
				var form_selected_id = data.selected.attr('data-g365_id');
        if( form_selected_id === "null" ) return;
				//string to connect all elements to support form type
				var form_input_target_id = data.searchField.attr('data-g365_form_dest');

        //see if we have to claim the user
				var form_selected_request = data.selected.attr('data-g365_request_access');
        if( typeof form_selected_request !== "undefined" ) {
          var form_field_user_id = data.searchField.attr('data-ls_user_ac');
          var form_field_type = data.searchField.attr('data-g365_type');
          form_field_user_id = form_field_user_id.split('-');
          if( typeof form_field_user_id !== "undefined" && typeof form_field_type !== "undefined" ) {
            //send request to grassroots for claiming
            $.when( g365_claim_start( form_field_type, form_selected_request ) )
            .done( function(claim_result){
              //handle results
              $('<p id="' + (form_input_target_id + '_success_message') + '" class="success">' + claim_result.message[form_field_type] + '</p>').insertAfter(data.searchField);
            });
          } else {
            $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Missing user or target data. Please contact a representative.</p>').insertAfter(data.searchField);
          }
          data.searchField.trigger('ajaxlivesearch:hide_result');
          return false;
        }

				//data set to send for form field assebly
				var form_data = {
          go_flat : false
        };
        //clear any old messages
        data.searchField.siblings('.error, .success').remove();
				//type of search
				form_data.query_type = ( typeof data.searchField.attr('data-g365_type_new') !== 'undefined' ) ? data.searchField.attr('data-g365_type_new') : data.searchField.attr('data-g365_type');
				if( typeof form_data.query_type == 'undefined' || form_data.query_type === '' ) {
          $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Missing type. Form will not submit. Please reload the page.</p>').insertAfter(data.searchField);
					return;
				}
        
        //see if we need to collect requirement data
        var contributions_compile = g365_cross_check_reqs( data.searchField );
        if( contributions_compile === false ) {
          $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Missing required field data.</p>').insertAfter(data.searchField);
          return false;
        }
        //if we have hardcocded presets in the search results add them to the contributions
        if( typeof data.selected.attr('data-g365_presets') !== 'undefined' ) {
          if( contributions_compile === null ) contributions_compile = {'data' : {}};
          contributions_compile.hide = {};
          var search_presets = $.parseJSON(data.selected.attr('data-g365_presets'));
          if( typeof search_presets === 'object' ) $.each(search_presets, function(key, value){ contributions_compile.data[key] = value; contributions_compile.hide[key] = 'hide'; });
        }
        //attach any contributions we collected to the main form build object
        if( contributions_compile !== null ) form_data.contributions = contributions_compile.data;

        var form_input_target = $( '#' + form_input_target_id );
				//element to add new form fields
				var form_new_wrapper = $( '#' + form_input_target_id + '_data' );

        //make sure that we aren't running into any limitations on the add
        var limits = data.searchField.attr('data-g365_limit');
        var dropdown_select = false;
        var dropdown_id;
        if( typeof limits !== 'undefined') {
          var break_out = false;
          limits = limits.split('|');
          $.each(limits, function(dex, limit_key){
            var limit_vals = limit_key.split(',');
            switch( limit_vals[0] ){
              case 'max':
                if( form_new_wrapper.children().length >= limit_vals[1] ) {
                  $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Maximum reached.</p>').insertAfter(data.searchField);
                  break_out = true;
                  return false;
                }
                break;
              case 'exceptions':
                //see this is an exception, and if we need to mark
                if(typeof data.selected.attr('data-g365_exception') !== 'undefined') {
                  if( contributions_compile === null ) contributions_compile = {'attrs': {}};
                  contributions_compile.attrs['data-g365_exception'] = '';
                  var limit_val = (parseInt(limit_vals[1]) === 0) ? 2 : 0;
                  if( limit_val !== 0 && form_new_wrapper.children('[data-g365_exception]').length >= limit_val ) {
                    $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Exception maximum reached.</p>').insertAfter(data.searchField);
                    break_out = true;
                    return false;
                  }
                }
                break;
              case 'dropdown':
                var dropdown_keys = form_new_wrapper.children('[data-g365_dropdown_key]').map( function(){ return $(this).attr('data-g365_dropdown_key'); } ).get();
                dropdown_select = $('#' + limit_vals[1]).children('option:selected');
                if( dropdown_select.length !== 1 ){
                  $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Cannot find unique listing.</p>').insertAfter(data.searchField);
                  break_out = true;
                  return false;
                } else {
                  dropdown_id = dropdown_select.html();
                  var dupe = false;
                  $.each(dropdown_keys, function(dex,key_val){ if( key_val === dropdown_id ) dupe = true; return false; });
                  if( dropdown_keys.length > 0 && dupe ) {
                    $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Event slot already in use.</p>').insertAfter(data.searchField);
                    break_out = true;
                    return false;
                  }
                }
                break;
              case 'only':
                //get the vars to use in the 'only' lock
                var only_fields = [];
                $.each(limit_vals.slice(1), function(dex, val){only_fields[only_fields.length] = val;});
                //get all the current data_key vars from the elements already in place
                var key_fields = {};
                form_new_wrapper.children('.g365_form').each(function(){
                  var this_fieldset = $(this);
                  //use fieldset id to create unique reference
                  var this_fieldset_id = this_fieldset.attr('id');
                  key_fields[this_fieldset_id] = {};
                  //collect all the data_keys for this fieldset
                  $( '[data-g365_data_key]', this_fieldset).each(function(){
                    var this_key = $(this);
                    key_fields[this_fieldset_id][this_key.attr('data-g365_data_key')] = this_key.val();
                  });
                });
                //loop through all the established fieldsets to see if we have any full matches
                $.each(key_fields, function(set_id, set_vars){
                  if( form_selected_id === set_vars.id  ) {
                    $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Duplicate entries not allowed.</p>').insertAfter(data.searchField);
                    break_out = true;
                    return false;
                  }
                });
                break;
            }
          });
          if( break_out ) return false;
        }
        //set the form to build with
        form_data.template_format = (typeof data.searchField.attr('data-g365_form_template') !== 'undefined') ? data.searchField.attr('data-g365_form_template') : 'form_template_min';
        //build the field_group var
        form_data.field_group = data.query_id;
        //if we have contributions, make a unique trail to each element out of them
        if( contributions_compile !== null) $.each(contributions_compile.data, function(key, val){ if( val !== "0" && $.isNumeric(val) && key !== data.searchField.attr('data-ls_target') ) form_data.field_group += '_' + val; });
        //make sure that we don't already have a control set for this data point in play
        if( $( '#' + form_data.field_group + '_' + form_selected_id ).length > 0 ) {
          $('<p id="' + (form_selected_id + '_error_message') + '" class="error">There is already a field set for the selected entry.</p>').insertAfter(data.searchField);
          return;
        }
				//are we loading data from the server to update or adding new data
				if( typeof form_selected_id !== 'undefined' && typeof parseInt(form_selected_id) === 'number' ) {
			 		form_data.id = [form_selected_id];
				} else {
          //add default args to build object
			 		form_data.id = null;
          var new_destination = data.searchField.attr('data-g365_form_dest_new');
          if( typeof new_destination !== 'undefined' ) form_input_target_id = new_destination;
          //set the form to build with a new template if provided
          if( typeof data.searchField.attr('data-g365_form_template_new') !== 'undefined' ) form_data.template_format = data.searchField.attr('data-g365_form_template_new');
				}
        //if we are going flat, set the variable
        var go_flat = data.searchField.attr('data-g365_base_id');
        if( typeof go_flat !== 'undefined' ) form_data.go_flat = go_flat;
        //if we are missing the wrapper, create one
        if( form_new_wrapper.length === 0 ) form_new_wrapper = $( '<div class="new_form" id="' + form_input_target_id + '_data"></div>' ).appendTo( data.searchField.parent().parent() );
        form_data.field_origin_id = data.searchField.attr('id');
        //if there is no target or wrapper get out
				if( typeof form_input_target_id == 'undefined' || form_new_wrapper.length !== 1 ) {
          $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Missing target or target element. Form will not submit. Please reload the page.</p>').insertAfter(data.searchField);
					return;
				}
				//build form and add it to the fields holder
        // function located in: g365_form_manager.js
				$.when(g365_build_template_from_data( form_data ))
				.done( function(form_template_message){
          //attach the new form and set a handle
          var form_new_loaded = '';
          //set a handle for the new form element, add presets, and attach the new form
          if( typeof form_template_message === 'object' ) {
            if( form_template_message.enabled === null && form_template_message.disabled === null ) {
              form_new_loaded = $( '<p>No data found.</p>' ).prependTo( form_new_wrapper );
            } else {
              if( form_template_message.disabled === null ) {
                g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( form_new_wrapper );
              } else if( form_template_message.enabled === null ) {
                g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( form_new_wrapper );
              } else {
                //attach the new form for both disabled and enabled data points
                var enabled_div = $('.g365_enabled_data', load_target);
                if( enabled_div.length === 0 ) {
                  g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( form_new_wrapper );
                  g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( form_new_wrapper );
                } else {
                  g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( $('.g365_enabled_data', form_new_wrapper) );
                  g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( $('.g365_disabled_data', form_new_wrapper) );
                }
//                 g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( $('.g365_enabled_data', form_new_wrapper) );
//                 g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( $('.g365_disabled_data', form_new_wrapper) );
              }
              form_new_loaded = form_new_wrapper;
            }
          } else {
            //attach the new form
      //       form_new_loaded = $( form_template_message ).prependTo( $('.form-holder>form>div:first-child', current_form) );
            form_new_loaded = g365_add_presets( $( form_template_message ), contributions_compile ).prependTo( form_new_wrapper );
          }
					//initialize form_data
          if( typeof data.searchField.attr('data-g365_form_template_new') !== 'undefined' ) data.searchField.val('');
					g365_form_start_up( form_new_loaded );
          //if this isn't nested show the submit buttons now that we have a form
          $( '#' + form_input_target_id + '_submit' ).show();
					//hide results
					data.searchField.trigger('ajaxlivesearch:hide_result');
				});
			},
			select_data: function(e, data) {
        //clear any old messages
        data.searchField.siblings('.error, .success').remove();
				//string to connect all elements to support form type
				var ls_target = data.searchField.attr('data-ls_target');
        //see if we have to claim the user
				var form_selected_request = data.selected.attr('data-g365_request_access');//grab the player id of who the user is submitting the form for
        if( typeof form_selected_request !== "undefined" ) {
          var form_field_user_id = data.searchField.attr('data-ls_user_ac');//id of user submitting the claim
          var form_field_type = data.searchField.attr('data-g365_type');//get the type that is being sent
          //new
          var pl_name = data.selected[0].innerText;
          form_field_user_id = form_field_user_id.split('-'); //get rid of the SPD and only use the id
          if( typeof form_field_user_id !== "undefined" && typeof form_field_type !== "undefined" ) {
            
            openClaimModal(pl_name, form_field_type, form_selected_request, ls_target);
//             //send request to grassroots for claiming
//             $.when( g365_claim_start( form_field_type, form_selected_request ) )
//             .done( function(claim_result){
//               //handle results
//               $('<p id="' + (ls_target + '_success_message') + '" class="success">' + claim_result.message[form_field_type] + '</p>').insertAfter(data.searchField);
//             });
          } else {
            $('<p id="' + (ls_target + '_error_message') + '" class="error">Missing user or target data. Please contact a representative.</p>').insertAfter(data.searchField);
          }
          data.searchField.trigger('ajaxlivesearch:hide_result');
          return false;
        }
				//the id of existing data or the kill command
				var form_selected_id = data.selected.attr('data-g365_id');
        if( form_selected_id === "null" ) return;
				//element to add new form fields
				var form_target = $( '#' + ls_target );
				if( typeof ls_target == 'undefined' || form_target.length !== 1 ) {
          $('<p id="' + (ls_target + '_error_message') + '" class="error">Missing target or target element. Form will not submit. Please reload the page.</p>').insertAfter(data.searchField);
					return false;
				}
        //if we have an id to put in the designated field, or we need to see about getting one
        if( typeof form_selected_id === 'undefined'  ){
          //make sure the data target is empty if we are adding a new record
          form_target.val('');
          //see if we are allowed to add to this datatype
          if( data.searchField.attr('data-ls_no_add') === 'true' ) return false;
          //if we have a click target and we don't want to add data inline, use that to maintain continuity
          if( typeof data.searchField.attr('data-g365_select_click') !== 'undefined' && typeof data.searchField.attr('data-g365_form_dest_new') !== 'undefined' ) {
            var form_click_target = $( '#' + data.searchField.attr('data-g365_select_click') );
            if( form_click_target.length === 1 ) form_click_target.click();
            return false;
          }
          //load the form so that we can collect the data
          ls_action_functions.load_form(e,data);
          if( typeof data.searchField.attr('data-g365_type_new') === 'undefined' ) data.searchField.parent().slideUp();
        } else {
          //revert all the subsequent dependancies before change
          revert_dependance_chain( $('#' + data.searchField.attr('data-load_target')) );
          var val_name = data.selected.attr('data-g365_name');
          if( typeof val_name === 'undefined' ) val_name = data.selected.attr('data-g365_short_name');
          data.searchField.val( val_name );

          //see if there is additional data on the selected entry
//           var additional_data = data.selected.attr('data-g365_additional_data');
//           additional_data = (( typeof additional_data === 'undefined' || additional_data === '' ) ? null : additional_data);
          //see if we have a division that need to be handled
//           var additional_target = data.searchField.attr('data-g365_additional_target');
//           console.log('select', additional_target, additional_data);
//           //set the data var to null if we don't have any data so the function will unload everything
//           if( typeof additional_target !== 'undefined' ) g365_manage_additional_data(additional_target, additional_data );
          
          //if we need to build wit a local object
//           var local_build_target = data.searchField.attr('data-g365_local_build_target');
//           if( typeof local_build_target != 'undefined' ) g365_build_dropdown_from_object( $('#' + local_build_target) );

          //set main query fields
          if(typeof data.selected.attr('data-g365_additional_data') !== 'undefined') form_target.attr( 'data-g365_additional_data', data.selected.attr('data-g365_additional_data') );
          if(typeof data.selected.attr('data-g365_short_name') !== 'undefined') form_target.attr( 'data-g365_short_name', data.selected.attr('data-g365_short_name') );
          form_target.val( form_selected_id ).change();
          //set the values we pushed incase we need to revert the field.
          var targets_compile = {
            target_val: form_selected_id,
            target_name: data.selected.attr('data-g365_name')
          };
          data.searchField.data('compare_vals', targets_compile);
        }
				data.searchField.trigger('ajaxlivesearch:hide_result');
        //if the toggle button flag is set
        if(data.searchField.attr('data-g365_field_toggle') === 'true') {
          if( form_target.val() === '' ) return;
          var search_field_toggle = data.searchField.closest('.ls_container').parent();
          var field_change_button = search_field_toggle.prev('.field-change-button');
          if( field_change_button.length === 0 ) field_change_button = $('<a class="field-change-button block text-right tiny-padding" style="display:none;"></a>').insertBefore(search_field_toggle);
          field_change_button.html('<span class="field-title">' + data.searchField.val() + '</span><span class="field-button">change</span>').show();
          field_change_button.on('click',function(){ $(this).hide().next().show(); });
          search_field_toggle.hide();
        }
        //if we have an add button controlled by this element, toggle appropriately
        g365_manage_add_button( form_target );
        //adds a click on 'data-g365_select_click' target at the end of the process
        if( typeof form_target.val() !== 'undefined' && form_target.val() !== '' && typeof data.searchField.attr('data-g365_select_click') === 'string' ) {
          $('#' + data.searchField.attr('data-g365_select_click')).click();
//           var select_click_target = $('#' + data.searchField.attr('data-g365_select_click'));
//           if( select_click_target.length == 1 && typeof select_click_target.attr('data-g365_join_data') === 'string' && select_click_target.attr('data-g365_join_data') === 'true' ) {
//             select_click_target.click();
//           } else {
//             select_click_target.click();
//           }
        }
			}
		}
		this.each(function ( dex ) {
      var query = $(this);
      var query_id = ( ls.decendant_id === false ) ? ls.form_class + '_' + query.attr('data-g365_type') + '_' + query.attr('id') + '_' + dex : ls.decendant_id + '_' + dex;
      //action of the livesearch: load_form, navigate, select_data
      var ls_action = query.attr('data-g365_action');
      //if the 'click' action is going to be different than the 'enter' action set it with 'data-g365_action_click'
      var ls_action_click = query.attr('data-g365_action_click');
      //if there isn't a definited action, default to navigate
      if( typeof ls_action === 'undefined' ) ls_action = 'navigate';
      if( typeof ls_action_click === 'undefined' ) ls_action_click = ls_action;
      //if we need a target, and there isn't one, send a notice

      switch( ls_action ) {
        case 'navigate':
          break;
        case 'select_data':
          var ls_target = $( '#' + query.attr('data-ls_target') );
          if( ls_target === 0 ) console.log('Need input target. This form will not submit.', query);
          if( typeof query.attr('data-g365_form_dest') === 'undefined' && query.attr('data-ls_no_add') !== 'true' ) console.log('Need input destination. This form will not submit.', query);
          //just for live searches that lock/control multiple fields
          if( typeof ls_target.attr('data-g365_load_target') !== 'undefined' && ls_target.attr('data-g365_load_target').charAt(0) === '.' )   ls_target.on('change', function(){ g365_build_dropdown_from_object( $(this) ); });

          query.on('focusout resetter', function(evnt){
            //make sure we aren't clicking on one of the navigation arrows
            if( $(evnt.relatedTarget).hasClass("ls-arrow") ) {
  //             $(evnt.relatedTarget).on('focusout', function(arrow_evnt){
  //               if( $(arrow_evnt.relatedTarget) !== query )
  //             });
              return;
            }
            //if either value is empty, empty the other
            var search_input = $(this);
            var revert_targets = search_input.data( 'compare_vals' );
            var search_input_val = search_input.val();
            var ls_target_val = ls_target.val();
            if( search_input_val === '' || ls_target_val === '' || (typeof revert_targets === 'object' && ( (revert_targets.target_name !== search_input_val && revert_targets.target_val === ls_target_val) || (revert_targets.target_name === search_input_val && revert_targets.target_val !== ls_target_val) )) ) {
              ls_target.attr('data-g365_short_name', '');
              ls_target.attr('data-g365_additional_data', '');
              if( ls_target.val() !== '' ) ls_target.val('').change();
              if( search_input.val() !== '' ) search_input.val('').change();
              //if we have an add button controlled by this element, toggle appropriately
              g365_manage_add_button( ls_target );
            }
          });
          break;
        case 'load_form':
          var form_dest = query.attr('data-g365_form_dest');
          if( typeof form_dest === 'undefined' ) console.log('Need input target. This form will not submit.', query);
          break;
      }
      //add the click and enter handlers to individual search elements
      var onResultEnter = ls_action_functions[ls_action];
      var onResultClick = ls_action_functions[ls_action_click];
      generateFormHtml(query, query_id, ls);
      var form = $('#' + query_id);
      var result = getFormInfo(form, 'result', ls);
      var arrow = getFormInfo(form, 'arrow', ls);
      var current_page = getFormInfo(form, 'current_page', ls);
      var current_page_lbl = getFormInfo(form, 'current_page_lbl', ls);
      var total_page_lbl = getFormInfo(form, 'total_page_lbl', ls);
      var page_range = getFormInfo(form, 'page_range', ls);
      /**
       * Start binding
       */

      // Trigger search when typing is started
      query.on('keyup', function (event) {
        // If enter key is pressed check if the user wants to select hovered row
        var keycode = event.keyCode || event.which;
        if ($.trim(query.val()).length && keycode === 13) {
          if (!(result.is(":visible") || result.is(":animated")) || parseInt(result.find("tr").length) === 0) {
              show_result(result, ls);
          } else {
            if (query.selected_row !== undefined) {
              var data = {selected: $(query.selected_row), this: this, searchField: query, query_id: query_id};
              if (onResultEnter !== undefined) {
                onResultEnter(event, data);
              }
            }
          }
          event.stopPropagation();
        } else {
          // If something other than enter is pressed start search immediately
          search(this, form, ls, false, true);
        }
      });

				/**
				 * While search input is in focus
				 * Move among the rows, by pressing or keep pressing arrow up and down
				 */
				query.on('keydown', function (event) {
						var keycode = event.keyCode || event.which;
						if (keycode === 40 || keycode === 38) {
								if ($.trim(query.val()).length && result.find("tr").length !== 0) {
										if (result.is(":visible") || result.is(":animated")) {
												result.find('tr').removeClass('hover');

												if (query.selected_row === undefined) {
														// Moving just started
														query.selected_row = result.find("tr").eq(0);
														$(query.selected_row).addClass("hover");
												} else {
														$(query.selected_row).removeClass("hover");

														if (keycode === 40) {
																// next
																if ($(query.selected_row).next().length === 0) {
																		// here is the end of the table
																		query.selected_row = result.find("tr").eq(0);
																		$(query.selected_row).addClass("hover");
																} else {
																		$(query.selected_row).next().addClass("hover");
																		query.selected_row = $(query.selected_row).next();
																}
														} else {
																// previous
																if ($(query.selected_row).prev().length === 0) {
																		// here is the end of the table
																		query.selected_row = result.find("tr").last();
																		query.selected_row.addClass("hover");
																} else {
																		$(query.selected_row).prev().addClass("hover");
																		query.selected_row = $(query.selected_row).prev();
																}
														}
												}
										} else {
												// If there is any results and hidden and the search input is in focus, show result by press down
												if (keycode === 40) {
														show_result(result, ls);
												}
										}
								}

								// prevent cursor from jumping to beginning or end of input
								return false;
						}
				});

				// Show result when is focused
				query.on('focus', function () {
						// check if the result is not empty show it
						if ($.trim(query.val()).length && (result.is(":hidden") || result.is(":animated")) && result.find("tr").length !== 0) {
								search(this, form, ls, false, true);
								show_result(result, ls);
						}
				});

				// In the beginning, there is no result / tr, so we bind the event to the future tr
				result.on('mouseover', 'tr', function () {
						// remove all the hover classes, otherwise there are more than one hovered rows
						result.find('tr').removeClass('hover');

						// set the current selected row
						query.selected_row = this;

						$(this).addClass('hover');
				});

				// In the beginning, there is no result / tr, so we bind the event to the future tr
				result.on('mouseleave', 'tr', function () {
						// remove all the hover classes, otherwise there are more than one hovered rows
						result.find('tr').removeClass('hover');

						// Reset selected row
						query.selected_row = undefined;
				});

				// disable the form submit on pressing enter
				form.submit(function () {
						return false;
				});

				// arrow button - next
				arrow.on('click', function () {
						var new_current_page;

						if ($(this).hasClass(ls.next_page_class)) {
								// go next if it will be lower or equal to the total
								if (parseInt(current_page.val(), 10) + 1 <= parseInt(total_page_lbl.html(), 10)) {
										new_current_page = parseInt(current_page.val(), 10) + 1;
								} else {
										return;
								}
						} else {
								// previous button
								if (parseInt(current_page.val(), 10) - 1 >= 1) {
										new_current_page = parseInt(current_page.val(), 10) - 1;
								} else {
										return;
								}
						}

						current_page.val(new_current_page);
						current_page_lbl.html(new_current_page);

						// search again
						search(query[0], form, ls, true, false);
				});

				// Search again when the items per page dropdown is changed
				page_range.on('change', function () {
						/**
						 * we need to pass a DOM Element: query[0]
						 * In this case last value should not check against the current one
						 */
						search(query[0], form, ls, true, true);
				});

//             result.css({left: query.position().left + 1, width: query.outerWidth() - 2});

//             // re-Adjust result position when screen resizes
//             $(window).resize(function () {
//                 //adjust_result_position();
//                 result.css({left: query.position().left + 1, width: query.outerWidth() - 2});
//             });

				/**
				 * Click doesn't work on iOS - This is to fix that
				 * According to: http://stackoverflow.com/a/9380061/2045041
				 */
				var touchStartPos;
				$(document)
						// log the position of the touchstart interaction
						.bind('touchstart', function () {
								touchStartPos = $(window).scrollTop();
						})
						// log the position of the touchend interaction
						.bind('touchend', function (event) {
								// calculate how far the page has moved between
								// touchstart and end.
								var distance, clickableItem;

								distance = touchStartPos - $(window).scrollTop();

								clickableItem = $(document);

								/**
								 * adding this class for devices that
								 * will trigger a click event after
								 * the touchend event finishes. This
								 * tells the click event that we've
								 * already done things so don't repeat
								 */
								clickableItem.addClass("touched");

								if (distance < 10 && distance > -10) {
										/**
										 * Distance was less than 20px
										 * so we're assuming it's tap and not swipe
										 */
										if (!$(event.target).closest(result).length && !$(event.target).is(query) && $(result).is(":visible")) {
												hide_result(result, ls);
										}
								}
						});

				$(document).on('click', function (event) {
						/**
						 * For any non-touch device, we need to still apply a click event but we'll first check to see if there
						 * was a previous touch event by checking for the class that was left by the touch event.
						 */
						if ($(this).hasClass("touched")) {
								/**
								 * This item's event was already triggered via touch so we won't call the function and reset this
								 * for the next touch by removing the class
								 */
								$(this).removeClass("touched");
						} else {
								/**
								 * There wasn't a touch event. We're instead using a mouse or keyboard hide the result if outside
								 * of the result is clicked
								 */
								if (!$(event.target).closest(result).length && !$(event.target).is(query) && $(result).is(":visible")) {
										hide_result(result, ls);
								}
						}
				});
				/**
				 * Finish binding
				 */

				/**
				 * Custom Events
				 */
				$(result).on('click', 'tr', function (e) {
          var data = {selected: $(query.selected_row), this: this, searchField: query, query_id: query_id};
          if (onResultClick !== undefined) {
            onResultClick(e, data);
          }
        });
                
        $('#addBtnProfile').on('click', function (e) {
          var data = {selected: $(query.selected_row), this: this, searchField: query, query_id: query_id};
          if (onResultClick !== undefined) {
            onResultClick(e, data);
            $(this).hide();
          }
				});

				/**
				 * Custom Triggers
				 */
				$(this).on('ajaxlivesearch:hide_result', function () {
          hide_result(result, ls);
				});
				$(this).on('ajaxlivesearch:search', function (e, params) {
          $(this).val(params.query);
          search(this, form, ls, true, true);
        });
		});
		// Set anti bot value for those that do not have JavaScript enabled
		$('.' + ls.form_anti_bot_class).val(ls.form_anti_bot);
		// keep chaining
		return this;
}
function g365_livesearch_init( parent_limit, query_id ){
	var g365_live_search_input = ( typeof parent_limit == 'undefined' ) ? $(".g365_livesearch_input") : $(".g365_livesearch_input", parent_limit);
	var ls_decendant_id = ( typeof query_id === 'undefined' || query_id === null ) ? false : query_id;
	//see if we have any ls inputs to activate
	if ( g365_live_search_input.length > 0 ) {
		g365_live_search_input.ajaxlivesearch({
			loaded_at: g365_sess_data.time,
			token: g365_sess_data.token,
			session: g365_sess_data.id,
			target_url: g365_script_domain,
			decendant_id: ls_decendant_id,
			max_input: 60,
			footer_class: 'ls_result_footer',
			page_range_default: 10,
			table_class: 'unstriped compact expanded no-margin-bottom',
			onAjaxComplete: function(e, data) {
        if( e.result === 'remove-session' ) {
          g365_cookies.remove( 'g365_SID', {domain: g365_script_domain.slice(8,-1)} );
//           g365_sess_data = false;
          location.reload();
        }
				console.log('complete');
			}
		});
	}
}
window.g365_func_wrapper.sess[window.g365_func_wrapper.sess.length] = {name : g365_livesearch_init, args : []};
//connect the cart to the g365 form
$( document.body ).on( 'updated_cart_totals', function(){
  //get all the qty inputs
  var form_qty_elements = $('div.woocommerce .qty');
  //loop through the entire global json tree
  $.each(g365_form_details.items, function(cat_id, cat_vals){
    $.each(cat_vals.items, function(prod_id, prod_vals){
      $.each(prod_vals.vars, function(var_id, var_vals){
        //defaults to eliminate, unless the global is claimed by a qty
        var eliminate = true;
        //loop through all the qty inputs and cross check with the global json
        form_qty_elements.each(function(){
          //pull all the data we need to perform the check
          var data_element = $(this);
          var product_cat = data_element.attr('data-prod-cat');
          var product_id = data_element.attr('data-prod-id');
          var product_var_id = data_element.attr('data-prod-var-id');
          //exit if any of our variables are weird or don't match
          if(
            isNaN(parseInt(product_cat)) || product_cat === '' ||
            isNaN(parseInt(product_id)) || product_id === '' ||
            isNaN(parseInt(product_var_id)) || product_var_id === '' ||
            product_cat != cat_id || product_id != prod_id || (product_var_id != var_id && product_var_id !== 0)
          ) return;
          //if we have a match then prevent elimination
          eliminate = false;
          //update the quantity of the global json
          g365_form_details.items[cat_id].items[prod_id].vars[var_id].qty = data_element.val();
        }); //end qty elements
        if( eliminate ) delete g365_form_details.items[cat_id].items[prod_id].vars[var_id];
      }); //end vars
      if( $.isEmptyObject(g365_form_details.items[cat_id].items[prod_id].vars) ) delete g365_form_details.items[cat_id].items[prod_id];
    }); //end prod
    //if we don't have any more items in the category, erase the global holder, otherwise run the g365 form manager
    if( $.isEmptyObject(g365_form_details.items[cat_id].items) ) {
      $('#' + g365_form_details.items[cat_id].target).closest('.form-holder').parent().remove();
      delete g365_form_details.items[cat_id];
    } else {
      g365_manage_cart_form( $('#' + g365_form_details.items[cat_id].target), g365_form_details.items[cat_id].type, g365_form_details.items[cat_id].items );
    }
  }); //end cat
});

//add cart updater for the quantity change
$('div.woocommerce').on( 'change', '.qty', function(){ $( '#update_cart' ).trigger('click'); });

var woo_comm_form = $('#woocommerce-checkout-form');
//activate the input to support data if we have the g365_form_details object
if( typeof g365_form_details !== 'undefined' && Object.keys(g365_form_details.items).length > 0 ) {
  woo_comm_form.prepend('<input type="hidden" id="g365_order_data" name="order_data" value="null" />');
}


// //make sure that we check for g365 data before we try to submit the first woo checkout server validation
// woo_comm_form.on( 'checkout_place_order', function() {
//   // return true to continue the submission or false to prevent it
//   var error_present = false;
//   $('.form-init', '#g365_form_wrap').each(function(){
//     $('.form_loader', target_container).on('click', function(e){ e.preventDefault(); g365_build_form_from_data( $(this) ); });

//     if( g365_check_validation($(this)) === true ) error_present = true;
//   });
//   return error_present;
// });


//pause the form submit while we try and get the g365 data squared away
$(document.body).on( 'checkout_error', function() {
  $( 'html, body' ).stop();
  console.log('arrete');
  // return true to continue the submission or false to prevent it
  // get error messages
  var error_item = $('.woocommerce-error').find('li').first();
  console.log('plcer', error_item);
  if ( error_item.text().trim() == 'G365 form needs to be processed or completed.' ) {
    //form passed and we now want to process the g365 data
    //also hide the notice and stop any scrolling
    $.scroll_to_notices( $( '#place_order' ) );
    $('.woocommerce-error').remove();
    //try to submit the custom g356 form
    var g365_forms = $('#g365_form_wrap .primary-form');
    //set the send status to false;
    woo_comm_form.data('form_fired', false);
    g365_forms.each(function(){
      //reference to the form element 
      var current_set = $(this);
      //set the listener for the submission completion
      $('#' + current_set.attr('id') + '_message', current_set).on( 'result_complete', function(e, result_info) {
        //if there are no errors, add the result data and finish the form
        if( result_info.error_status === false ){
          //add it to the order_data object and try to submit the whole form again
          woo_comm_form.data(current_set.attr('data-g365_type') + '_g365', result_info.result_ids.join(','));
          //if we have the right amount of fieldsets results, add the data to the order_data field and submit the form, but screen out the keys that aren't ours
          var woo_form_data = woo_comm_form.data();
          var woo_form_data_keys = Object.keys(woo_comm_form.data()).filter(function(val){ return val.substr(val.length - 5) === '_g365'; });
          if( woo_form_data_keys.length === g365_forms.length && woo_form_data.form_fired === false ) {
            woo_comm_form.data('form_fired', true);
            var woo_order_data = [];
            var woo_order_add_data = [];
            $.each( woo_form_data_keys, function(dex, val){
              var val_name = val.substr(0, val.length-5);
              $.each( g365_form_details.items, function(cat_dex, cat_vals) {
                if( cat_vals.type == val_name ) {
                  inc_status = false;
                  return false;
                }
              });
              if( inc_status ) return;
              woo_order_data[woo_order_data.length] = val_name + ',' + woo_form_data[val];
              if( typeof result_info.result_add[woo_form_data[val]] !== 'undefined' ) woo_order_add_data[woo_order_add_data.length] = val_name + ',' + woo_form_data[val] + ':::' + result_info.result_add[woo_form_data[val]];
            });
            //get a reference to the target field to add our form output
            var order_data_ele = $('#g365_order_data');
            //join the array and write the val
            order_data_ele.val(woo_order_data.join('|'));
            //if we processed additional data, add it it's own field
            if( woo_order_add_data.length > 0 ) order_data_ele.parent().prepend($('<input type="hidden" id="g365_order_data_add" name="order_data_add" value="' + woo_order_add_data.join('|') + '">'));
            //continue the order submission
            woo_comm_form.submit();
            return false;
          }
        }
      });
      current_set.submit();
    });
    return false;
  }
  console.log('no extra forms found, submitting....');
//   return false;
  return true;
});


var g365_cookies = Cookies.withConverter({
  write: function(value) {
    // Encode all characters according to the "encodeURIComponent" spec
    return encodeURIComponent(value)
      // Revert the characters that are unnecessarly encoded but are
      // allowed in a cookie value, except for the plus sign (%2B)
      .replace(/%(23|24|26|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);
  },
  read: function(value) {
    return value
      // Decode the plus sign to spaces first, otherwise "legit" encoded pluses
      // will be replaced incorrectly
      .replace(/\+/g, ' ')
      // Decode all characters according to the "encodeURIComponent" spec
      .replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
  }
});
function g365_session_init() {
  window.g365_sess_data = g365_cookies.getJSON().g365_SID;
  if(
    typeof window.g365_sess_data !== 'object' ||
    typeof window.g365_sess_data.id !== 'string' || window.g365_sess_data.id === '' ||
    typeof window.g365_sess_data.token !== 'string' || window.g365_sess_data.token === '' ||
    typeof window.g365_sess_data.time !== 'number' || window.g365_sess_data.time === 0
  ) {
    $.ajax({
      type: "post",
      url: g365_script_domain + 'session-gen/',
      headers: {'X-Requested-With': 'XMLHttpRequest', 'uni_tag': g365_script_domain},
      dataType: "json",
      cache: false,
      success: function (response) {
        if (response.status === 'success') {
          g365_cookies.set( 'g365_SID', response.result, {domain: g365_script_domain.slice(8,-1), expires: 14, secure: true, sameSite: 'none' } );
          window.g365_sess_data = response.result;
          if (window.g365_func_wrapper.sess.length > 0) window.g365_func_wrapper.sess.forEach( function(func){ func.name.apply(null, func.args); });
        } else {
          // There is an error
          console.log('ResObj Error: ',response.result);
        }
      },
      error: function (response) {
        console.log('Ajax error.', response, response.responseText);
        if( response.responseText.indexOf('<b>Fatal error</b>') !== -1  ) {
          var error_count = g365_cookies.get('g365_ajax_error_auto_retry');
          if( error_count === 'undefined' ) {
            g365_cookies.set('g365_ajax_error_auto_retry', 'true', { expires: 0.0001, path: '' });
            location.reload();
          }
        }
      }
    });
  } else {
    if (window.g365_func_wrapper.sess.length > 0) window.g365_func_wrapper.sess.forEach( function(func){ func.name.apply(null, func.args); });
    window.g365_func_wrapper.sess_init = 1;
  }
}
if( typeof g365_script_domain !== 'undefined' ) g365_session_init();
})(jQuery);