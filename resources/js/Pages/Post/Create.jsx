import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import DangerButton from '@/Components/DangerButton';
import TextInput from '@/Components/TextInput';
import { Transition } from '@headlessui/react';

export default function Create() {

    const { data, setData, post, errors, processing, recentlySuccessful } =
        useForm({
            title: '',
            body: '',
        });

    const submit = (e) => {
        e.preventDefault();

        post(route('posts.store'));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Crear Nuevo Post
                </h2>
            }
        >
            <Head title="Posts" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">

                                <section className="max-w-xl">
                                    <header>
                                        <p className="mt-1 text-sm text-gray-600">
                                            Crea un nuevo post para que visualice entre tu listado
                                        </p>
                                    </header>

                                    <form onSubmit={submit} className="mt-6 space-y-6">
                                        <div>
                                            <InputLabel htmlFor="title" value="Titulo" />

                                            <TextInput
                                                id="name"
                                                className="mt-1 block w-full"
                                                value={data.title}
                                                onChange={(e) => setData('title', e.target.value)}
                                                required
                                                isFocused
                                                autoComplete="title"
                                            />

                                            <InputError className="mt-2" message={errors.title} />
                                        </div>

                                        <div>
                                            <InputLabel htmlFor="body" value="Contenido" />

                                            <textarea
                                                placeholder="Contenido"
                                                className="mt-1 block w-full"
                                                value={data.body}
                                                onChange={(e) => setData('body', e.target.value)}
                                            />

                                            <InputError className="mt-2" message={errors.body} />
                                        </div>


                                        <div className="flex items-center gap-4">
                                            <DangerButton disabled={processing}>
                                                <Link href="/posts" className="btn">Regresar</Link>
                                            </DangerButton>

                                            <PrimaryButton disabled={processing}>Guardar</PrimaryButton>

                                            <Transition
                                                show={recentlySuccessful}
                                                enter="transition ease-in-out"
                                                enterFrom="opacity-0"
                                                leave="transition ease-in-out"
                                                leaveTo="opacity-0"
                                            >
                                                <p className="text-sm text-gray-600">
                                                    Saved.
                                                </p>
                                            </Transition>
                                        </div>
                                    </form>
                                </section>

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
