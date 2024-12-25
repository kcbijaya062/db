using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using MovieLists.Models;

namespace MovieLists.Controllers
{
    public class HomeController : Controller
    {
        private MovieContext context { get; set; }

        public HomeController(MovieContext ctx) =>
            context = ctx;

        public IActionResult Index()
        {
            var moviesWithGenres = context.Movies.Include(m => m.Genre).OrderBy(m => m.Name).ToList();
            return View(moviesWithGenres);
        }
    }
}
