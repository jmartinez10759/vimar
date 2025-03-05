import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import DangerButton from '@/Components/DangerButton';
import TextInput from '@/Components/TextInput';
import { Transition } from '@headlessui/react';

export default function PostList({ posts, user }) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Posts
                </h2>
            }
        >
            <Head title="Posts" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <PrimaryButton>
                                <Link href="/posts/create" className="btn">Crear Nuevo Post</Link>
                            </PrimaryButton>
                            <br/><br/>
                            <table className="w-full border-collapse border border-gray-300">
                                <thead className="bg-gray-100">
                                    <tr>
                                        <th className="border p-3 text-left" >ID</th>
                                        <th className="border p-3 text-left" >Título</th>
                                        <th className="border p-3 text-center" >Contenido</th>
                                        <th colSpan="2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {posts.data.map(post => (
                                        <tr key={post.id} className="hover:bg-gray-50">
                                            <td className="border p-3">{post.id}</td>
                                            <td className="border p-3">{post.title}</td>
                                            <td className="border p-3">{post.body}</td>
                                            <td className="border p-3 text-center flex justify-center gap-2">

                                                <PrimaryButton>
                                                    <Link href={route('posts.edit', post.id)}>
                                                        <button>Editar</button>
                                                    </Link>
                                                </PrimaryButton>

                                            </td>
                                            <td className="border p-3 text-center flex justify-center gap-2">
                                                    <DangerButton>
                                                        <Link
                                                            href={`/posts/${post.id}`}
                                                            method="delete"
                                                            as="button"
                                                            className="btn"
                                                        >
                                                            Eliminar
                                                        </Link>
                                                    </DangerButton>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>

                            <div className="flex justify-center mt-4 space-x-2">
                                {posts.links.map((link) => (
                                    <Link key={link.label} href={link.url} className={`px-4 py-2 rounded ${link.active ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black'}`} dangerouslySetInnerHTML={{ __html: link.label }} />

                                ))}
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
