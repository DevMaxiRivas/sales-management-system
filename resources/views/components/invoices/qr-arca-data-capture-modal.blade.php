<?php

use Livewire\Component;

new class extends Component {
    protected $listeners = [
        'send-info' => 'sendInfoEvent',
    ];

    public function openModal()
    {
        $this->dispatch('open-modal', id: 'capture-data-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', id: 'capture-data-modal');
    }

    public function sendInfoEvent($data)
    {
        $this->closeModal();
        $this->dispatch('qr-info-captured', ['qr_data' => $data]);
    }
};
?>
<div>

    <x-filament::modal id="capture-data-modal" :close-by-clicking-away="false" :close-button="false">
        <style>
            #camera video {
                width: 100%;
                max-width: 640px;
            }

            #camera .drawingBuffer {
                display: none;
            }

            /* QR JS */
            #camera-video {
                width: 100%;
            }
        </style>
        <x-slot name="trigger">
            <x-filament::button color="info" size="xl" icon="heroicon-o-qr-code" wire:click="$js.enableCamera()">
                ARCA
            </x-filament::button>
        </x-slot>

        <x-slot name="heading">
            Capture invoice data
        </x-slot>

        <x-slot name="description">
            Scan the ARCA QR code on the invoice to capture the details automatically.
            If you don’t have the QR code, you can enter the details manually.
            <div class="camera-container pb-0">
                <video id="camera-video" autoplay playsinline></video>
            </div>
            <div id="info-camera-container" class="d-none">
                <p id="qr-result" class="fi-modal-description"></p>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end gap-4">
                <x-filament::button wire:click="closeModal" color="danger" size="xl">
                    Cancel
                </x-filament::button>
            </div>
        </x-slot>

        @assets
            <script src="{{ asset('js/qrs/jsQR.min.js') }}"></script>
        @endassets

        @script
            <script>
                const video = document.getElementById('camera-video');
                let stream;

                function decodeData(encodedData) {
                    console.log(encodedData);
                    const decodedData = atob(decodeURIComponent(encodedData));
                    try {
                        return JSON.parse(decodedData);
                    } catch (error) {
                        if (error instanceof SyntaxError) {
                            const documentTypeError = '"nroDocRec":00000000';

                            if (decodedData.includes(documentTypeError)) {
                                decodedData = decodedData.replace('"nroDocRec":00000000', '"nroDocRec":0')
                            }

                            return JSON.parse(decodedData);
                        }

                        alert("Decoding error. Please try again by refreshing the page.");
                        console.error("Decoding error:", error);
                        return null;
                    }
                }

                function prepareData(rawData) {
                    let data = {
                        receipt_number: rawData.nroCmp,
                        point_of_sale: rawData.ptoVta,
                        company_tax_id: rawData.cuit,
                        amount: rawData.importe,
                        date: rawData.fecha,
                    };

                    switch (rawData.tipoDocRec) {
                        case 96: //DNI
                            data.customer_type = "DNI";
                            data.customer_id = rawData.nroDocRec;
                            break;
                        case 80: //CUIT
                            data.customer_type = "CUIT";
                            data.customer_id = rawData.nroDocRec;
                            break;
                        case 99: //OTHER
                            data.customer_type = "UNREGISTERED";
                            break;
                        default:
                            data.customer_type = "UNKNOWN";
                    }

                    return data;
                }

                function validardata(data) {
                    if (data.cuit && data.nroCmp && data.ptoVta && data.importe && data.fecha) {
                        return true;
                    }
                    return false;
                }

                function stopCamera() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        video.srcObject = null;
                    }
                }

                function tick() {
                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        const canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        const context = canvas.getContext('2d');
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

                        const code = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: "dontInvert",
                        });

                        if (code) {
                            let dataURL = code.data.split("?p=");
                            if (dataURL.length > 1) {
                                const dataDecoded = decodeData(dataURL[1]);
                                sendInfo(dataDecoded);
                                stopCamera();
                                return;
                            }
                        }
                    }

                    requestAnimationFrame(tick);
                }

                function sendInfo(data) {
                    Livewire.dispatch('send-info', {
                        data: prepareData(data)
                    });
                }

                $js('enableCamera', () => {
                    navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: "environment"
                            }
                        })
                        .then(function(s) {
                            stream = s;
                            video.srcObject = stream;
                            // required to tell iOS safari we don't want fullscreen
                            video.setAttribute("playsinline", true);
                            video.play();
                            requestAnimationFrame(tick);
                        })
                        .catch(function(err) {
                            const qrResult = document.getElementById('qr-result');
                            const cameraContainer = document.querySelector('.camera-container');
                            cameraContainer.remove();
                            qrResult.style.display = 'block';
                            qrResult.textContent = "Error accessing the camera: " + err.message;
                        });

                })
            </script>
        @endscript
    </x-filament::modal>
</div>
