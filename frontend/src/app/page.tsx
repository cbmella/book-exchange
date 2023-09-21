import Image from 'next/image'

export default function Home() {
  return (
    <div>
      <h1>xdasdas</h1>
      <p>Home page</p>
      <p>
        <a href="/contact">Contact</a>
      </p>
      <p>
        <a href="/about">About</a>
      </p>
      <Image src="/vercel.svg" alt="Vercel Logo" width={72} height={16} />
    </div>
  )
}
